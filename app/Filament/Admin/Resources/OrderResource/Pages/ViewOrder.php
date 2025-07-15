<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción de edición principal
            Actions\EditAction::make()
                ->label('Editar Pedido')
                ->icon('heroicon-o-pencil')
                ->color('warning'),

            // Acciones de cambio de estado específicas
            Actions\Action::make('confirm')
                ->label('Confirmar Pedido')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_PENDING)
                ->requiresConfirmation()
                ->modalHeading('Confirmar Pedido')
                ->modalDescription('¿Estás seguro de que deseas confirmar este pedido?')
                ->form([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas adicionales')
                        ->placeholder('Agregar notas sobre la confirmación...')
                        ->rows(3),
                ])
                ->action(function (Order $record, array $data): void {
                    $record->updateStatus(Order::STATUS_CONFIRMED, $data['notes'] ?? null);
                    Notification::make()
                        ->title('Pedido confirmado exitosamente')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('startPreparing')
                ->label('Iniciar Preparación')
                ->icon('heroicon-o-clock')
                ->color('primary')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_CONFIRMED)
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas de preparación')
                        ->placeholder('Agregar instrucciones especiales...')
                        ->rows(3),
                ])
                ->action(function (Order $record, array $data): void {
                    $record->updateStatus(Order::STATUS_IN_PROCESS, $data['notes'] ?? null);
                    Notification::make()
                        ->title('Preparación iniciada')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('markReady')
                ->label('Marcar como Listo')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_IN_PROCESS)
                ->requiresConfirmation()
                ->action(function (Order $record): void {
                    $record->updateStatus(Order::STATUS_READY);
                    Notification::make()
                        ->title('Pedido marcado como listo')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('dispatch')
                ->label('Despachar')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_READY)
                ->requiresConfirmation()
                ->form([
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Número de seguimiento')
                        ->placeholder('Opcional'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas de despacho')
                        ->placeholder('Instrucciones para el repartidor...')
                        ->rows(3),
                ])
                ->action(function (Order $record, array $data): void {
                    $record->update(['tracking_number' => $data['tracking_number'] ?? null]);
                    $record->updateStatus(Order::STATUS_OUT_FOR_DELIVERY, $data['notes'] ?? null);
                    Notification::make()
                        ->title('Pedido despachado')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('deliver')
                ->label('Marcar como Entregado')
                ->icon('heroicon-o-home')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_OUT_FOR_DELIVERY)
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas de entrega')
                        ->placeholder('Detalles de la entrega...')
                        ->rows(3),
                ])
                ->action(function (Order $record, array $data): void {
                    $record->updateStatus(Order::STATUS_DELIVERED, $data['notes'] ?? null);
                    Notification::make()
                        ->title('Pedido entregado exitosamente')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('complete')
                ->label('Completar Pedido')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === Order::STATUS_DELIVERED)
                ->requiresConfirmation()
                ->action(function (Order $record): void {
                    $record->updateStatus(Order::STATUS_COMPLETED);
                    Notification::make()
                        ->title('Pedido completado')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('cancel')
                ->label('Cancelar Pedido')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (Order $record): bool => $record->canBeCancelled())
                ->requiresConfirmation()
                ->modalHeading('Cancelar Pedido')
                ->modalDescription('Esta acción no se puede deshacer.')
                ->form([
                    Forms\Components\Textarea::make('cancellation_reason')
                        ->label('Razón de cancelación')
                        ->required()
                        ->placeholder('Explica el motivo de la cancelación...')
                        ->rows(3),
                ])
                ->action(function (Order $record, array $data): void {
                    $record->update(['cancellation_reason' => $data['cancellation_reason']]);
                    $record->updateStatus(Order::STATUS_CANCELLED, $data['cancellation_reason']);
                    Notification::make()
                        ->title('Pedido cancelado')
                        ->warning()
                        ->send();
                }),

            // Acción para cambio de estado personalizado
            Actions\Action::make('changeStatus')
                ->label('Cambiar Estado')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Nuevo Estado')
                        ->options(fn (Order $record): array => $this->getAvailableStatuses($record))
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
                    Notification::make()
                        ->title("Estado cambiado a: {$statusName}")
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getAvailableStatuses(Order $record): array
    {
        $allStatuses = Order::getStatuses();
        $currentStatus = $record->status;
        
        // Definir transiciones válidas
        $validTransitions = [
            Order::STATUS_PENDING => [
                Order::STATUS_CONFIRMED,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_CONFIRMED => [
                Order::STATUS_IN_PROCESS,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_IN_PROCESS => [
                Order::STATUS_READY,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_READY => [
                Order::STATUS_OUT_FOR_DELIVERY,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_OUT_FOR_DELIVERY => [
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_DELIVERED => [
                Order::STATUS_COMPLETED,
            ],
        ];

        $availableStatuses = $validTransitions[$currentStatus] ?? [];
        
        return array_intersect_key($allStatuses, array_flip($availableStatuses));
    }
}
