<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use App\Models\Zone;
use App\Models\Franchise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Gestión de Pedidos';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?string $pluralModelLabel = 'Pedidos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Pedido')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->label('Número de Pedido')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->default(fn () => 'ORD-' . strtoupper(uniqid())),
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options(Order::getStatuses())
                                    ->required()
                                    ->native(false)
                                    ->default(Order::STATUS_PENDING),
                                Forms\Components\Select::make('payment_status')
                                    ->label('Estado de Pago')
                                    ->options(Order::getPaymentStatuses())
                                    ->required()
                                    ->native(false)
                                    ->default(Order::PAYMENT_STATUS_PENDING),
                            ]),
                    ]),

                Section::make('Cliente y Ubicación')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Cliente')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('franchise_id')
                                    ->label('Franquicia')
                                    ->relationship('franchise', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('zone_id')
                                    ->label('Zona')
                                    ->relationship('zone', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('payment_method')
                                    ->label('Método de Pago')
                                    ->options([
                                        'cash' => 'Efectivo',
                                        'card' => 'Tarjeta',
                                        'transfer' => 'Transferencia',
                                        'wompi' => 'Wompi',
                                        'openpay' => 'OpenPay',
                                        'globalpay' => 'GlobalPay',
                                        'wenjoy' => 'Wenjoy',
                                    ])
                                    ->native(false),
                            ]),
                    ]),

                Section::make('Información del Cliente')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label('Nombre del Cliente')
                                    ->required(),
                                Forms\Components\TextInput::make('customer_phone')
                                    ->label('Teléfono del Cliente')
                                    ->tel(),
                                Forms\Components\TextInput::make('customer_email')
                                    ->label('Email del Cliente')
                                    ->email(),
                            ]),
                    ]),

                Section::make('Direcciones')
                    ->schema([
                        Forms\Components\Repeater::make('delivery_address')
                            ->label('Dirección de Entrega')
                            ->schema([
                                Forms\Components\TextInput::make('street')
                                    ->label('Dirección')
                                    ->required(),
                                Forms\Components\TextInput::make('city')
                                    ->label('Ciudad'),
                                Forms\Components\TextInput::make('postal_code')
                                    ->label('Código Postal'),
                                Forms\Components\Textarea::make('notes')
                                    ->label('Notas de Dirección'),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        Forms\Components\Repeater::make('delivery_coordinates')
                            ->label('Coordenadas de Entrega')
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitud')
                                    ->numeric(),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitud')
                                    ->numeric(),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),

                Section::make('Montos')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('tax_amount')
                                    ->label('Impuestos')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('shipping_amount')
                                    ->label('Envío')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('discount_amount')
                                    ->label('Descuento')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Fechas y Seguimiento')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\DateTimePicker::make('scheduled_delivery_date')
                                    ->label('Fecha Programada de Entrega'),
                                Forms\Components\DateTimePicker::make('estimated_delivery_time')
                                    ->label('Tiempo Estimado de Entrega'),
                                Forms\Components\TextInput::make('tracking_number')
                                    ->label('Número de Seguimiento'),
                            ]),
                    ]),

                Section::make('Notas e Instrucciones')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3),
                        Forms\Components\Textarea::make('special_instructions')
                            ->label('Instrucciones Especiales')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state)
                    ->colors([
                        'secondary' => Order::STATUS_PENDING,
                        'warning' => Order::STATUS_CONFIRMED,
                        'primary' => Order::STATUS_IN_PROCESS,
                        'info' => Order::STATUS_READY,
                        'warning' => Order::STATUS_OUT_FOR_DELIVERY,
                        'success' => [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED],
                        'danger' => Order::STATUS_CANCELLED,
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pago')
                    ->formatStateUsing(fn (string $state): string => Order::getPaymentStatuses()[$state] ?? $state)
                    ->colors([
                        'secondary' => Order::PAYMENT_STATUS_PENDING,
                        'warning' => Order::PAYMENT_STATUS_PROCESSING,
                        'success' => Order::PAYMENT_STATUS_PAID,
                        'danger' => Order::PAYMENT_STATUS_FAILED,
                        'info' => Order::PAYMENT_STATUS_REFUNDED,
                    ]),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Franquicia')
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_delivery_date')
                    ->label('Entrega Programada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Order::getStatuses()),
                SelectFilter::make('payment_status')
                    ->label('Estado de Pago')
                    ->options(Order::getPaymentStatuses()),
                SelectFilter::make('franchise')
                    ->label('Franquicia')
                    ->relationship('franchise', 'name'),
                SelectFilter::make('zone')
                    ->label('Zona')
                    ->relationship('zone', 'name'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Desde'),
                        DatePicker::make('created_until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Cambiar Estado')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Nuevo Estado')
                            ->options(Order::getStatuses())
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->updateStatus($data['status'], $data['notes']);
                    }),
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
            // RelationManagers\ItemsRelationManager::class,
            // RelationManagers\PaymentsRelationManager::class,
            // RelationManagers\StatusHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
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
