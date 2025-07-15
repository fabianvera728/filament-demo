<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class OrderStatusStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $stats = Cache::remember('order_stats', 300, function () {
            return [
                'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
                'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
                'in_process' => Order::where('status', Order::STATUS_IN_PROCESS)->count(),
                'ready' => Order::where('status', Order::STATUS_READY)->count(),
                'out_for_delivery' => Order::where('status', Order::STATUS_OUT_FOR_DELIVERY)->count(),
                'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
                'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
                'cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
                'total_today' => Order::whereDate('created_at', today())->count(),
                'revenue_today' => Order::whereDate('created_at', today())
                    ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                    ->sum('total_amount'),
            ];
        });

        return [
            Stat::make('Pedidos Pendientes', $stats['pending'])
                ->description('Esperando confirmación')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$dispatch("filterByStatus", { status: "pending" })',
                ]),

            Stat::make('En Preparación', $stats['in_process'])
                ->description('Siendo preparados')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$dispatch("filterByStatus", { status: "in_process" })',
                ]),

            Stat::make('Listos para Entrega', $stats['ready'])
                ->description('Esperando despacho')
                ->descriptionIcon('heroicon-m-check')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$dispatch("filterByStatus", { status: "ready" })',
                ]),

            Stat::make('En Camino', $stats['out_for_delivery'])
                ->description('Siendo entregados')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$dispatch("filterByStatus", { status: "out_for_delivery" })',
                ]),

            Stat::make('Pedidos Hoy', $stats['total_today'])
                ->description('Total del día')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('gray'),

            Stat::make('Ingresos Hoy', '$' . number_format($stats['revenue_today'], 2))
                ->description('Pagos confirmados')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
} 