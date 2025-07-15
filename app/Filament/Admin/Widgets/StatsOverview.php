<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
use App\Models\Franchise;
use App\Models\Product;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Usuarios', User::count())
                ->description('Usuarios registrados en el sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Pedidos Hoy', Order::whereDate('created_at', today())->count())
                ->description('Pedidos realizados hoy')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Franquicias Activas', Franchise::where('is_active', true)->count())
                ->description('Franquicias operativas')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),

            Stat::make('Productos Disponibles', Product::where('is_active', true)->count())
                ->description('Productos en catálogo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Ventas del Mes', '$' . number_format(
                Order::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'delivered')
                    ->sum('total_amount'), 0, ',', '.'
            ))
                ->description('Ingresos del mes actual')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Pedidos Pendientes', Order::whereIn('status', ['pending', 'confirmed', 'preparing'])->count())
                ->description('Pedidos en proceso')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
