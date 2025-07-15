<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneResource\Pages;
use App\Filament\Resources\ZoneResource\RelationManagers;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\TernaryFilter;

class ZoneResource extends Resource
{
    protected static ?string $model = Zone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $modelLabel = 'Zona';

    protected static ?string $pluralModelLabel = 'Zonas';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Básica')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code')
                                    ->label('Código')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10),
                                Forms\Components\Textarea::make('description')
                                    ->label('Descripción')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Activa')
                                    ->default(true),
                            ]),
                    ]),

                Section::make('Configuración de Entrega')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('delivery_fee')
                                    ->label('Tarifa de Entrega')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('min_order_amount')
                                    ->label('Monto Mínimo de Pedido')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('estimated_delivery_time')
                                    ->label('Tiempo Estimado de Entrega (minutos)')
                                    ->numeric()
                                    ->suffix('min'),
                            ]),
                    ]),

                Section::make('Ubicación Geográfica')
                    ->schema([
                        Forms\Components\Repeater::make('coordinates')
                            ->label('Coordenadas')
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitud')
                                    ->numeric()
                                    ->step(0.000001),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitud')
                                    ->numeric()
                                    ->step(0.000001),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        Forms\Components\Repeater::make('coverage_area')
                            ->label('Área de Cobertura')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre del Área')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Descripción'),
                            ])
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->label('Tarifa de Entrega')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_order_amount')
                    ->label('Monto Mínimo')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_delivery_time')
                    ->label('Tiempo Estimado')
                    ->suffix(' min')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Usuarios')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pedidos')
                    ->counts('orders')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todas')
                    ->trueLabel('Activas')
                    ->falseLabel('Inactivas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\UsersRelationManager::class,
            // RelationManagers\OrdersRelationManager::class,
            // RelationManagers\FranchisesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZones::route('/'),
            'create' => Pages\CreateZone::route('/create'),
            'view' => Pages\ViewZone::route('/{record}'),
            'edit' => Pages\EditZone::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
} 