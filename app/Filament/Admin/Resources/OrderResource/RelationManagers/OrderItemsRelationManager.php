<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Product;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Productos del Pedido';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $product = Product::find($state);
                            if ($product) {
                                $set('product_name', $product->name);
                                $set('product_sku', $product->sku);
                                $set('product_description', $product->description);
                                $set('unit_price', $product->price);
                                $set('product_image', $product->image);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('product_name')
                    ->label('Nombre del Producto')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('product_sku')
                    ->label('SKU')
                    ->maxLength(100),

                Forms\Components\Textarea::make('product_description')
                    ->label('Descripción')
                    ->rows(2),

                Forms\Components\TextInput::make('unit_price')
                    ->label('Precio Unitario')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        $quantity = $get('quantity') ?? 1;
                        $set('total_price', $state * $quantity);
                    }),

                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        $unitPrice = $get('unit_price') ?? 0;
                        $set('total_price', $state * $unitPrice);
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->label('Precio Total')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->disabled(),

                Forms\Components\KeyValue::make('product_options')
                    ->label('Opciones del Producto')
                    ->keyLabel('Opción')
                    ->valueLabel('Valor')
                    ->addActionLabel('Agregar Opción'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                Tables\Columns\ImageColumn::make('product_image')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-product.png')),

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Precio Unit.')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_options')
                    ->label('Opciones')
                    ->formatStateUsing(function ($state) {
                        if (!$state || !is_array($state)) {
                            return '-';
                        }
                        return collect($state)->map(function ($value, $key) {
                            return "{$key}: {$value}";
                        })->join(', ');
                    })
                    ->wrap()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar Producto'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
