<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FranchiseResource\Pages;
use App\Filament\Admin\Resources\FranchiseResource\RelationManagers;
use App\Models\Franchise;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FranchiseResource extends Resource
{
    protected static ?string $model = Franchise::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    
    protected static ?string $navigationLabel = 'Franquicias';
    
    protected static ?string $modelLabel = 'Franquicia';
    
    protected static ?string $pluralModelLabel = 'Franquicias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Principal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\Select::make('zone_id')
                            ->label('Zona')
                            ->relationship('zone', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Información de Contacto')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('address')
                            ->label('Dirección')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->label('Ciudad')
                            ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make('Configuración Operacional')
                    ->schema([
                        Forms\Components\TimePicker::make('opening_time')
                            ->label('Hora de Apertura'),

                        Forms\Components\TimePicker::make('closing_time')
                            ->label('Hora de Cierre'),

                        Forms\Components\TextInput::make('delivery_radius_km')
                            ->label('Radio de Entrega (km)')
                            ->numeric()
                            ->suffix('km'),

                        Forms\Components\TextInput::make('commission_percentage')
                            ->label('Porcentaje de Comisión (%)')
                            ->numeric()
                            ->suffix('%')
                            ->step(0.01),

                        Forms\Components\TextInput::make('minimum_order_amount')
                            ->label('Monto Mínimo de Pedido')
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.00000001),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.00000001),

                        Forms\Components\TextInput::make('manager_name')
                            ->label('Nombre del Gerente')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('manager_phone')
                            ->label('Teléfono del Gerente')
                            ->tel()
                            ->maxLength(20),
                    ])->columns(3),

                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),

                        Forms\Components\CheckboxList::make('operating_days')
                            ->label('Días de Operación')
                            ->options([
                                'lunes' => 'Lunes',
                                'martes' => 'Martes',
                                'miércoles' => 'Miércoles',
                                'jueves' => 'Jueves',
                                'viernes' => 'Viernes',
                                'sábado' => 'Sábado',
                                'domingo' => 'Domingo',
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('opening_time')
                    ->label('Horario')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('Comisión (%)')
                    ->suffix('%')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('manager_name')
                    ->label('Gerente')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
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

                Tables\Filters\SelectFilter::make('zone_id')
                    ->label('Zona')
                    ->relationship('zone', 'name')
                    ->multiple()
                    ->preload(),
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
            'index' => Pages\ListFranchises::route('/'),
            'create' => Pages\CreateFranchise::route('/create'),
            'view' => Pages\ViewFranchise::route('/{record}'),
            'edit' => Pages\EditFranchise::route('/{record}/edit'),
        ];
    }
}
