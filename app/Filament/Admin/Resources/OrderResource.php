<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Pedidos';
    
    protected static ?string $modelLabel = 'Pedido';
    
    protected static ?string $pluralModelLabel = 'Pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número de Pedido')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(Order::getStatuses())
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, $context) {
                                // Auto-completar timestamps según el estado
                                if ($context === 'create') return;
                                
                                match ($state) {
                                    Order::STATUS_CONFIRMED => $set('confirmed_at', now()),
                                    Order::STATUS_IN_PROCESS => $set('prepared_at', now()),
                                    Order::STATUS_OUT_FOR_DELIVERY => $set('dispatched_at', now()),
                                    Order::STATUS_DELIVERED => $set('delivered_at', now()),
                                    Order::STATUS_CANCELLED => $set('cancelled_at', now()),
                                    Order::STATUS_COMPLETED => $set('completed_at', now()),
                                    default => null,
                                };
                            })
                            ->helperText('El estado determina qué campos son editables'),

                        Forms\Components\Select::make('payment_status')
                            ->label('Estado de Pago')
                            ->options(Order::getPaymentStatuses())
                            ->required()
                            ->searchable()
                            ->reactive(),

                        Forms\Components\Select::make('delivery_method')
                            ->label('Método de Entrega')
                            ->options([
                                'delivery' => 'Domicilio',
                                'pickup' => 'Recoger en tienda',
                                'dine_in' => 'Comer en el lugar',
                            ])
                            ->required()
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Cliente y Asignaciones')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),

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

                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nombre del Cliente')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email del Cliente')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Teléfono del Cliente')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(3),

                Forms\Components\Section::make('Información de Entrega')
                    ->schema([
                        Forms\Components\TextInput::make('delivery_address')
                            ->label('Dirección de Entrega')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('delivery_city')
                            ->label('Ciudad')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('delivery_state')
                            ->label('Departamento/Estado')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('delivery_postal_code')
                            ->label('Código Postal')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('delivery_country')
                            ->label('País')
                            ->required()
                            ->default('Colombia')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('delivery_instructions')
                            ->label('Instrucciones de Entrega')
                            ->rows(3),
                    ])->columns(3),

                Forms\Components\Section::make('Montos y Pagos')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Impuestos')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        Forms\Components\TextInput::make('shipping_amount')
                            ->label('Costo de Envío')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Descuento')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        Forms\Components\TextInput::make('tip_amount')
                            ->label('Propina')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                'cash' => 'Efectivo',
                                'card' => 'Tarjeta',
                                'transfer' => 'Transferencia',
                                'wompi' => 'Wompi',
                                'openpay' => 'Openpay',
                                'globalpay' => 'GlobalPay',
                                'wenjoy' => 'Wenjoy',
                            ])
                            ->searchable(),

                        Forms\Components\Select::make('currency')
                            ->label('Moneda')
                            ->options([
                                'COP' => 'Peso Colombiano (COP)',
                                'USD' => 'Dólar Americano (USD)',
                            ])
                            ->default('COP')
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Fechas y Tiempos')
                    ->schema([
                        Forms\Components\DateTimePicker::make('confirmed_at')
                            ->label('Confirmado en')
                            ->displayFormat('d/m/Y H:i'),

                        Forms\Components\DateTimePicker::make('prepared_at')
                            ->label('Preparado en')
                            ->displayFormat('d/m/Y H:i'),

                        Forms\Components\DateTimePicker::make('dispatched_at')
                            ->label('Despachado en')
                            ->displayFormat('d/m/Y H:i'),

                        Forms\Components\DateTimePicker::make('delivered_at')
                            ->label('Entregado en')
                            ->displayFormat('d/m/Y H:i'),

                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->label('Cancelado en')
                            ->displayFormat('d/m/Y H:i'),

                        Forms\Components\DateTimePicker::make('estimated_delivery_time')
                            ->label('Tiempo Estimado de Entrega')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(3),

                Forms\Components\Section::make('Notas y Observaciones')
                    ->schema([
                        Forms\Components\Textarea::make('customer_notes')
                            ->label('Notas del Cliente')
                            ->rows(3),

                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Razón de Cancelación')
                            ->rows(3),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('# Pedido')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'preparing' => 'primary',
                        'ready' => 'success',
                        'dispatched' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmado',
                        'preparing' => 'Preparando',
                        'ready' => 'Listo',
                        'dispatched' => 'Despachado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'processing' => 'Procesando',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('franchise.name')
                    ->label('Franquicia')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Entrega')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'delivery' => 'Domicilio',
                        'pickup' => 'Recoger',
                        'dine_in' => 'En el lugar',
                        default => $state,
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('delivery_city')
                    ->label('Ciudad')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('delivered_at')
                    ->label('Entregado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmado',
                        'preparing' => 'Preparando',
                        'ready' => 'Listo',
                        'dispatched' => 'Despachado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'processing' => 'Procesando',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('delivery_method')
                    ->label('Método de Entrega')
                    ->options([
                        'delivery' => 'Domicilio',
                        'pickup' => 'Recoger en tienda',
                        'dine_in' => 'Comer en el lugar',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('franchise_id')
                    ->label('Franquicia')
                    ->relationship('franchise', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\Filter::make('created_at')
                    ->label('Fecha de Creación')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('created_until')
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
                
                // Acciones de estado rápidas
                Tables\Actions\Action::make('quickConfirm')
                    ->label('Confirmar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->updateStatus(Order::STATUS_CONFIRMED);
                        \Filament\Notifications\Notification::make()
                            ->title('Pedido confirmado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('quickPrepare')
                    ->label('Preparar')
                    ->icon('heroicon-o-clock')
                    ->color('primary')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_CONFIRMED)
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->updateStatus(Order::STATUS_IN_PROCESS);
                        \Filament\Notifications\Notification::make()
                            ->title('Preparación iniciada')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('quickReady')
                    ->label('Listo')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_IN_PROCESS)
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->updateStatus(Order::STATUS_READY);
                        \Filament\Notifications\Notification::make()
                            ->title('Pedido listo')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('quickDispatch')
                    ->label('Despachar')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_READY)
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->updateStatus(Order::STATUS_OUT_FOR_DELIVERY);
                        \Filament\Notifications\Notification::make()
                            ->title('Pedido despachado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('quickDeliver')
                    ->label('Entregar')
                    ->icon('heroicon-o-home')
                    ->color('success')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_OUT_FOR_DELIVERY)
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->updateStatus(Order::STATUS_DELIVERED);
                        \Filament\Notifications\Notification::make()
                            ->title('Pedido entregado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('quickCancel')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->size('sm')
                    ->visible(fn (Order $record): bool => $record->canBeCancelled())
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar Pedido')
                    ->modalDescription('¿Estás seguro de que deseas cancelar este pedido?')
                    ->form([
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Razón de cancelación')
                            ->required()
                            ->placeholder('Explica el motivo...')
                            ->rows(2),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update(['cancellation_reason' => $data['cancellation_reason']]);
                        $record->updateStatus(Order::STATUS_CANCELLED, $data['cancellation_reason']);
                        \Filament\Notifications\Notification::make()
                            ->title('Pedido cancelado')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\Action::make('updateStatus')
                    ->label('Cambiar Estado')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Nuevo Estado')
                            ->options(Order::getStatuses())
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas del cambio')
                            ->placeholder('Explica el motivo del cambio...')
                            ->rows(3),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->updateStatus($data['status'], $data['notes'] ?? null);
                        $statusName = Order::getStatuses()[$data['status']] ?? $data['status'];
                        \Filament\Notifications\Notification::make()
                            ->title("Estado cambiado a: {$statusName}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Pedidos Seleccionados')
                        ->modalDescription('Esta acción no se puede deshacer.')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $deletedCount = 0;
                            $failedCount = 0;
                            
                            foreach ($records as $record) {
                                if (in_array($record->status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED])) {
                                    $record->delete();
                                    $deletedCount++;
                                } else {
                                    $failedCount++;
                                }
                            }
                            
                            if ($deletedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title("Eliminados {$deletedCount} pedidos")
                                    ->success()
                                    ->send();
                            }
                            
                            if ($failedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title("No se pudieron eliminar {$failedCount} pedidos")
                                    ->body('Solo se pueden eliminar pedidos en estado pendiente o cancelado')
                                    ->warning()
                                    ->send();
                            }
                        }),

                    Tables\Actions\BulkAction::make('bulkConfirm')
                        ->label('Confirmar Seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $confirmedCount = 0;
                            
                            foreach ($records as $record) {
                                if ($record->status === Order::STATUS_PENDING) {
                                    $record->updateStatus(Order::STATUS_CONFIRMED);
                                    $confirmedCount++;
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title("Confirmados {$confirmedCount} pedidos")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulkCancel')
                        ->label('Cancelar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Cancelar Pedidos Seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas cancelar todos los pedidos seleccionados?')
                        ->form([
                            Forms\Components\Textarea::make('cancellation_reason')
                                ->label('Razón de cancelación')
                                ->required()
                                ->placeholder('Explica el motivo de la cancelación masiva...')
                                ->rows(3),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $cancelledCount = 0;
                            
                            foreach ($records as $record) {
                                if ($record->canBeCancelled()) {
                                    $record->update(['cancellation_reason' => $data['cancellation_reason']]);
                                    $record->updateStatus(Order::STATUS_CANCELLED, $data['cancellation_reason']);
                                    $cancelledCount++;
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title("Cancelados {$cancelledCount} pedidos")
                                ->warning()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulkChangeStatus')
                        ->label('Cambiar Estado')
                        ->icon('heroicon-o-arrow-path')
                        ->color('gray')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Nuevo Estado')
                                ->options(Order::getStatuses())
                                ->required()
                                ->native(false),
                            Forms\Components\Textarea::make('notes')
                                ->label('Notas del cambio')
                                ->placeholder('Explica el motivo del cambio masivo...')
                                ->rows(3),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $updatedCount = 0;
                            
                            foreach ($records as $record) {
                                $record->updateStatus($data['status'], $data['notes'] ?? null);
                                $updatedCount++;
                            }
                            
                            $statusName = Order::getStatuses()[$data['status']] ?? $data['status'];
                            \Filament\Notifications\Notification::make()
                                ->title("Actualizados {$updatedCount} pedidos a: {$statusName}")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Exportar Seleccionados')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            // Aquí iría la lógica de exportación
                            \Filament\Notifications\Notification::make()
                                ->title('Exportación iniciada')
                                ->body("Se exportarán {$records->count()} pedidos")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
            RelationManagers\StatusHistoryRelationManager::class,
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
}
