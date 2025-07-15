@extends('layouts.store')

@section('title', 'Carrito de Compras - Domisoft')

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
                        <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">Carrito de Compras</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Cart Items Section -->
            <div class="lg:col-span-8">
                <!-- Cart Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                        </svg>
                        Tu Carrito
                    </h1>
                    @if($cartItems->count() > 0)
                        <button class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" id="clear-cart">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Vaciar Carrito</span>
                        </button>
                    @endif
                </div>

                @if($cartItems->count() > 0)
                    <!-- Cart Items -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        @foreach($cartItems as $item)
                            <div class="cart-item border-b border-gray-100 last:border-b-0 p-6 hover:bg-gray-50 transition-colors" data-item-id="{{ $item->id }}">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
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
                                        <p class="text-xl font-bold text-green-600">${{ number_format($item->product_price, 0) }}</p>
                                        
                                        @if($item->product_options && is_array($item->product_options))
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach($item->product_options as $key => $value)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $key }}: {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($item->product && $item->product->stock_quantity < $item->quantity)
                                            <div class="mt-2 flex items-center text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-medium">Stock insuficiente</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex flex-col items-center space-y-3">
                                        <div class="flex items-center border border-gray-300 rounded-lg">
                                            <button class="qty-decrease px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors" type="button">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" class="quantity-input w-16 px-3 py-2 text-center border-0 focus:ring-0" 
                                                   value="{{ $item->quantity }}" min="1" max="99">
                                            <button class="qty-increase px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors" type="button">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Item Total and Actions -->
                                    <div class="flex flex-col items-end space-y-3">
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-gray-900 item-total">
                                                ${{ number_format($item->total_price, 0) }}
                                            </p>
                                        </div>
                                        <button class="remove-item flex items-center justify-center w-10 h-10 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar producto" type="button">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <a href="{{ route('store.products.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Continuar Comprando
                        </a>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-16">
                        <div class="mx-auto h-24 w-24 text-gray-400 mb-6">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">Tu carrito está vacío</h3>
                        <p class="text-gray-600 mb-8">¡Agrega algunos productos deliciosos y comienza a disfrutar!</p>
                        <a href="{{ route('store.products.index') }}" class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V6a4 4 0 00-8 0v1H5a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V9h1a1 1 0 100-2zM10 6a2 2 0 114 0v1h-4V6z"></path>
                            </svg>
                            Explorar Productos
                        </a>
                    </div>
                @endif
            </div>

            <!-- Order Summary Sidebar -->
            @if($cartItems->count() > 0)
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
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} productos)</span>
                            <span class="font-semibold text-gray-900" id="cart-subtotal">${{ number_format($cartTotal, 0) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Envío estimado</span>
                            <span class="text-gray-500">A calcular</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">IVA (19%)</span>
                            <span class="font-semibold text-gray-900" id="cart-tax">${{ number_format($cartTotal * 0.19, 0) }}</span>
                        </div>
                        
                        <hr class="border-gray-200">
                        
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total estimado</span>
                            <span class="text-2xl font-bold text-green-600" id="cart-total">${{ number_format($cartTotal * 1.19, 0) }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 space-y-3">
                        <a href="{{ route('store.checkout.index') }}" class="w-full btn-success text-center py-4 text-lg font-semibold block">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Proceder al Checkout
                        </a>
                        
                        <button class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors" data-modal="shareCartModal" type="button">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            Compartir Carrito
                        </button>
                    </div>

                    <!-- Trust Badges -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                        <div class="text-center space-y-2">
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Compra 100% segura
                            </div>
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Envío gratis en pedidos mayores a $50,000
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Te puede interesar
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @php
                            $recommendedProducts = \App\Models\Product::where('is_active', true)
                                ->where('stock_quantity', '>', 0)
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @foreach($recommendedProducts as $product)
                            <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             class="w-12 h-12 rounded-lg object-cover" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 25) }}</h4>
                                    <p class="text-sm font-semibold text-green-600">${{ number_format($product->price, 0) }}</p>
                                </div>
                                <button class="btn-add-to-cart flex-shrink-0 w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-colors" data-product-id="{{ $product->id }}" type="button">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Share Cart Modal -->
<div id="shareCartModal" class="fixed inset-0 bg-gray-600 bg-gray-500/10 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Compartir Carrito</h3>
                <button class="modal-close text-gray-400 hover:text-gray-600" type="button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">Comparte tu carrito con tus amigos:</p>
            <div class="space-y-2">
                <button class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center justify-center" type="button">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp
                </button>
                <button class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center justify-center" type="button">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-.962 6.502-.378 1.31-.731 1.31-.962 1.31-.293-.005-.618-.187-.618-.187s-4.139-2.674-5.581-3.469c-.362-.2-.362-.52-.362-.52s3.35-1.93 6.681-3.23c.209-.08.249-.327.249-.327-.05-.23-.336-.201-.336-.201-1.298.315-7.971 2.674-7.971 2.674s-.479.154-.69.154c-.213 0-.497-.154-.497-.154s-1.818-.992-3.636-1.785c-.362-.158-.362-.49-.362-.49s.478-.302.83-.462c1.818-.834 11.62-4.906 11.62-4.906s.506-.155.76-.155z"/>
                    </svg>
                    Telegram
                </button>
                <button class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center justify-center" type="button">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    Copiar Link
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Include CartManager --}}
<script type="module">
    import CartManager from '{{ asset('js/cart-manager.js') }}';
    
    /**
     * Initialize cart when DOM is loaded
     */
    document.addEventListener('DOMContentLoaded', () => {
        new CartManager();
    });
</script>

{{-- Fallback for browsers that don't support modules --}}
<script>
    // Check if modules are supported
    if (!window.supportsES6Modules) {
        // Fallback for older browsers
        const script = document.createElement('script');
        script.src = '{{ asset('js/cart-manager.js') }}';
        script.onload = function() {
            if (window.CartManager) {
                document.addEventListener('DOMContentLoaded', () => {
                    new window.CartManager();
                });
            }
        };
        document.head.appendChild(script);
    }
</script>
@endpush
@endsection 