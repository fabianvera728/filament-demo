@extends('layouts.store')

@section('title', 'Mis Pedidos - Domisoft')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Mis Pedidos
            </h1>
            <p class="text-gray-600 mt-2">Revisa el estado y detalles de todos tus pedidos</p>
        </div>

        @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Order Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pedido #{{ $order->order_number }}</h3>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                        @elseif($order->status === 'ready') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @switch($order->status)
                                            @case('pending')
                                                Pendiente
                                                @break
                                            @case('confirmed')
                                                Confirmado
                                                @break
                                            @case('preparing')
                                                Preparando
                                                @break
                                            @case('ready')
                                                Listo
                                                @break
                                            @case('delivered')
                                                Entregado
                                                @break
                                            @case('cancelled')
                                                Cancelado
                                                @break
                                            @default
                                                {{ ucfirst($order->status) }}
                                        @endswitch
                                    </span>
                                </div>
                                <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-green-600">${{ number_format($order->total_amount, 0) }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->items->sum('quantity') }} productos</p>
                                    </div>
                                    <a href="{{ route('store.orders.show', $order) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Ver Detalles
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Info -->
                        <div class="px-6 py-4">
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @switch($order->delivery_method)
                                                @case('delivery')
                                                    Domicilio
                                                    @break
                                                @case('pickup')
                                                    Recoger en tienda
                                                    @break
                                                @case('dine_in')
                                                    Consumir en local
                                                    @break
                                                @default
                                                    {{ ucfirst($order->delivery_method) }}
                                            @endswitch
                                        </p>
                                        @if($order->delivery_method === 'delivery' && $order->delivery_address)
                                            <p class="text-sm text-gray-500">{{ Str::limit($order->delivery_address, 40) }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                                        <p class="text-sm text-gray-500">
                                            @if($order->payment_status === 'pending')
                                                Pendiente de pago
                                            @elseif($order->payment_status === 'paid')
                                                Pagado
                                            @elseif($order->payment_status === 'failed')
                                                Falló el pago
                                            @else
                                                {{ ucfirst($order->payment_status) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Estimado</p>
                                        <p class="text-sm text-gray-500">{{ $order->estimated_delivery_time->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="px-6 py-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Productos del pedido</h4>
                            <div class="flex items-center space-x-4 overflow-x-auto">
                                @foreach($order->items->take(3) as $item)
                                    <div class="flex-shrink-0 flex items-center space-x-2">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                 class="w-10 h-10 object-cover rounded-lg" 
                                                 alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($item->product_name, 20) }}</p>
                                            <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->items->count() > 3)
                                    <div class="flex-shrink-0 text-sm text-gray-500">
                                        +{{ $order->items->count() - 3 }} más
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-6">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">No tienes pedidos aún</h3>
                <p class="text-gray-600 mb-8">¡Haz tu primer pedido y comienza a disfrutar de nuestros productos!</p>
                <a href="{{ route('store.products.index') }}" class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V6a4 4 0 00-8 0v1H5a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V9h1a1 1 0 100-2zM10 6a2 2 0 114 0v1h-4V6z"></path>
                    </svg>
                    Explorar Productos
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 