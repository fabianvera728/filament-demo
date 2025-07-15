@extends('layouts.store')

@section('title', 'Pedido #' . $order->order_number . ' - Domisoft')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('store.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('store.orders.index') }}" class="ml-1 md:ml-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">Mis Pedidos</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">Pedido #{{ $order->order_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Order Header -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Pedido #{{ $order->order_number }}
                        </h1>
                        <p class="text-gray-600 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
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
                </div>

                <!-- Order Information Cards -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información del Cliente
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Nombre:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Teléfono:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $order->customer_phone }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Email:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $order->customer_email }}</dd>
                            </div>
                            @if($order->customer_notes)
                                <div class="pt-2 border-t border-gray-100">
                                    <dt class="text-sm text-gray-600 mb-1">Notas del cliente:</dt>
                                    <dd class="text-sm text-gray-900">{{ $order->customer_notes }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Información de Entrega
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Método:</dt>
                                <dd class="text-sm font-medium text-gray-900">
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
                                </dd>
                            </div>
                            @if($order->delivery_method === 'delivery')
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Dirección:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_address }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Ciudad:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_city }}</dd>
                                </div>
                                @if($order->delivery_instructions)
                                    <div class="pt-2 border-t border-gray-100">
                                        <dt class="text-sm text-gray-600 mb-1">Instrucciones:</dt>
                                        <dd class="text-sm text-gray-900">{{ $order->delivery_instructions }}</dd>
                                    </div>
                                @endif
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Tiempo estimado:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $order->estimated_delivery_time->format('H:i') }}</dd>
                            </div>
                            @if($order->franchise)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Franquicia:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $order->franchise->name }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Productos del Pedido
                        </h2>
                    </div>
                    
                    <div class="overflow-hidden">
                        @foreach($order->items as $item)
                            <div class="border-b border-gray-100 last:border-b-0 p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                 class="w-20 h-20 object-cover rounded-lg shadow-sm" 
                                                 alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->product_name }}</h3>
                                        @if($item->product_sku)
                                            <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product_sku }}</p>
                                        @endif
                                        @if($item->product_description)
                                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($item->product_description, 100) }}</p>
                                        @endif
                                        <p class="text-lg font-bold text-green-600">${{ number_format($item->unit_price, 0) }}</p>
                                        
                                        @if($item->product_options && is_array($item->product_options))
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach($item->product_options as $key => $value)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $key }}: {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Quantity and Total -->
                                    <div class="flex flex-col items-end space-y-2">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                                            <p class="text-xl font-bold text-gray-900">${{ number_format($item->total_price, 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('store.orders.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Volver a Mis Pedidos
                    </a>
                    
                    <a href="{{ route('store.products.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Seguir Comprando
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-4 mt-8 lg:mt-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-24">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Resumen del Pedido
                        </h2>
                    </div>

                    <!-- Summary Details -->
                    <div class="px-6 py-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900">${{ number_format($order->subtotal, 0) }}</span>
                        </div>
                        
                        @if($order->shipping_amount > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-semibold text-gray-900">${{ number_format($order->shipping_amount, 0) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">IVA (19%)</span>
                            <span class="font-semibold text-gray-900">${{ number_format($order->tax_amount, 0) }}</span>
                        </div>
                        
                        @if($order->tip_amount > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Propina</span>
                                <span class="font-semibold text-gray-900">${{ number_format($order->tip_amount, 0) }}</span>
                            </div>
                        @endif
                        
                        <hr class="border-gray-200">
                        
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($order->total_amount, 0) }}</span>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        <h3 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Información de Pago
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Método:</span>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Estado:</span>
                                <span class="text-sm font-medium 
                                    @if($order->payment_status === 'pending') text-yellow-600
                                    @elseif($order->payment_status === 'paid') text-green-600
                                    @elseif($order->payment_status === 'failed') text-red-600
                                    @else text-gray-600
                                    @endif">
                                    @if($order->payment_status === 'pending')
                                        Pendiente
                                    @elseif($order->payment_status === 'paid')
                                        Pagado
                                    @elseif($order->payment_status === 'failed')
                                        Fallido
                                    @else
                                        {{ ucfirst($order->payment_status) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Moneda:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $order->currency }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                        <!-- Contact Support -->
                        <div class="px-6 py-4 border-t border-gray-200 bg-blue-50">
                            <h3 class="text-sm font-semibold text-blue-900 mb-2">¿Necesitas ayuda?</h3>
                            <p class="text-sm text-blue-700 mb-3">Contáctanos si tienes alguna pregunta sobre tu pedido</p>
                            <div class="flex flex-col space-y-2">
                                <a href="tel:+57300123456" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    +57 300 123 4567
                                </a>
                                <a href="mailto:pedidos@domisoft.com" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    pedidos@domisoft.com
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 