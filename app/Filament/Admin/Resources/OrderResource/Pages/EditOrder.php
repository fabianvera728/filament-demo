<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción para ver el pedido
            Actions\ViewAction::make()
                ->label('Ver Pedido')
                ->icon('heroicon-o-eye'),

            // Acción para duplicar el pedido
            Actions\Action::make('duplicate')
                ->label('Duplicar Pedido')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Duplicar Pedido')
                ->modalDescription('Se creará una copia de este pedido con estado pendiente.')
                ->action(function (Order $record): void {
                    $newOrder = $record->replicate([
                        'order_number',
                        'status',
                        'confirmed_at',
                        'prepared_at',
                        'dispatched_at',
                        'delivered_at',
                        'cancelled_at',
                        'completed_at',
                    ]);
                    
                    $newOrder->order_number = 'ORD-' . strtoupper(uniqid());
                    $newOrder->status = Order::STATUS_PENDING;
                    $newOrder->save();

                    // Duplicar items
                    foreach ($record->items as $item) {
                        $newItem = $item->replicate();
                        $newItem->order_id = $newOrder->id;
                        $newItem->save();
                    }

                    Notification::make()
                        ->title('Pedido duplicado exitosamente')
                        ->body("Nuevo pedido: {$newOrder->order_number}")
                        ->success()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Ver nuevo pedido')
                                ->url(OrderResource::getUrl('view', ['record' => $newOrder]))
                        ])
                        ->send();
                }),

            // Acción para recalcular totales
            Actions\Action::make('recalculateTotals')
                ->label('Recalcular Totales')
                ->icon('heroicon-o-calculator')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Recalcular Totales')
                ->modalDescription('Los totales del pedido se recalcularán basándose en los items actuales.')
                ->action(function (Order $record): void {
                    $subtotal = $record->items()->sum(\DB::raw('unit_price * quantity'));
                    $taxAmount = $subtotal * 0.19; // IVA del 19%
                    $totalAmount = $subtotal + $taxAmount + ($record->shipping_amount ?? 0) - ($record->discount_amount ?? 0) + ($record->tip_amount ?? 0);

                    $record->update([
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                    ]);

                    Notification::make()
                        ->title('Totales recalculados')
                        ->body("Nuevo total: $" . number_format($totalAmount, 2))
                        ->success()
                        ->send();
                }),

            // Acción para enviar notificación al cliente
            Actions\Action::make('notifyCustomer')
                ->label('Notificar Cliente')
                ->icon('heroicon-o-bell')
                ->color('info')
                ->visible(fn (Order $record): bool => !empty($record->customer_email) || !empty($record->customer_phone))
                ->form([
                    Forms\Components\Select::make('notification_type')
                        ->label('Tipo de notificación')
                        ->options([
                            'email' => 'Email',
                            'sms' => 'SMS',
                            'both' => 'Email y SMS',
                        ])
                        ->required()
                        ->default('email'),
                    
                    Forms\Components\Select::make('template')
                        ->label('Plantilla')
                        ->options([
                            'status_update' => 'Actualización de estado',
                            'delay_notification' => 'Notificación de retraso',
                            'ready_for_pickup' => 'Listo para recoger',
                            'custom' => 'Mensaje personalizado',
                        ])
                        ->required()
                        ->reactive(),
                    
                    Forms\Components\Textarea::make('custom_message')
                        ->label('Mensaje personalizado')
                        ->visible(fn (callable $get): bool => $get('template') === 'custom')
                        ->required(fn (callable $get): bool => $get('template') === 'custom')
                        ->rows(4),
                ])
                ->action(function (Order $record, array $data): void {
                    // Aquí iría la lógica para enviar notificaciones
                    // Por ahora solo mostramos una notificación de confirmación
                    
                    Notification::make()
                        ->title('Notificación enviada')
                        ->body("Se envió la notificación al cliente vía {$data['notification_type']}")
                        ->success()
                        ->send();
                }),

            // Acción para eliminar (solo si está en estado pendiente o cancelado)
            Actions\DeleteAction::make()
                ->visible(fn (Order $record): bool => in_array($record->status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED]))
                ->requiresConfirmation()
                ->modalHeading('Eliminar Pedido')
                ->modalDescription('Esta acción no se puede deshacer. ¿Estás seguro?'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Guardar Cambios'),
            
            Actions\Action::make('saveAndView')
                ->label('Guardar y Ver')
                ->action(function (): void {
                    $this->save();
                    $this->redirect(OrderResource::getUrl('view', ['record' => $this->record]));
                })
                ->color('success'),
            
            $this->getCancelFormAction()
                ->label('Cancelar'),
        ];
    }

    protected function beforeSave(): void
    {
        // Validar que no se editen ciertos campos en estados específicos
        $record = $this->record;
        $currentStatus = $record->status;
        
        if (in_array($currentStatus, [Order::STATUS_COMPLETED, Order::STATUS_DELIVERED])) {
            // En estados finales, solo permitir cambios en campos específicos
            $allowedFields = ['customer_notes', 'delivery_instructions', 'meta_data'];
            $changes = $record->getDirty();
            
            foreach ($changes as $field => $value) {
                if (!in_array($field, $allowedFields)) {
                    Notification::make()
                        ->title('Edición restringida')
                        ->body("No se pueden modificar ciertos campos en estado: " . Order::getStatuses()[$currentStatus])
                        ->warning()
                        ->send();
                    
                    // Revertir cambios no permitidos
                    $record->{$field} = $record->getOriginal($field);
                }
            }
        }
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Pedido actualizado')
            ->body('Los cambios se han guardado exitosamente')
            ->success()
            ->send();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Agregar campos calculados antes de llenar el formulario
        $data['total_items'] = $this->record->items()->sum('quantity');
        $data['items_count'] = $this->record->items()->count();
        
        return $data;
    }
}
