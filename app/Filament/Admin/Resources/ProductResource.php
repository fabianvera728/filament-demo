<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('short_description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                Forms\Components\TextInput::make('barcode'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('cost_price')
                    ->numeric(),
                Forms\Components\TextInput::make('compare_price')
                    ->numeric(),
                Forms\Components\TextInput::make('stock_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('min_stock_level')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('track_inventory')
                    ->required(),
                Forms\Components\Toggle::make('allow_backorder')
                    ->required(),
                Forms\Components\TextInput::make('weight')
                    ->numeric(),
                Forms\Components\Textarea::make('dimensions')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('images')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image')
                    ->image(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('franchise_id')
                    ->relationship('franchise', 'name'),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\Toggle::make('is_digital')
                    ->required(),
                Forms\Components\Toggle::make('requires_shipping')
                    ->required(),
                Forms\Components\Textarea::make('attributes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('variants')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('tags')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('seo_data')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rating_average')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rating_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('view_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('purchase_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('available_from'),
                Forms\Components\DateTimePicker::make('available_until'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-product.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Franquicia')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Destacados')
                    ->boolean()
                    ->trueLabel('Destacados')
                    ->falseLabel('No destacados')
                    ->native(false),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('franchise_id')
                    ->label('Franquicia')
                    ->relationship('franchise', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock Bajo')
                    ->query(fn (Builder $query): Builder => $query->whereColumn('stock_quantity', '<=', 'min_stock_level'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
