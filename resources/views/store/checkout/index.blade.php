@extends('layouts.store')

@section('title', 'Checkout - Finalizar Compra | Domisoft')

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
                        <a href="{{ route('store.cart.index') }}" class="ml-1 md:ml-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">Carrito</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">Checkout</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Carrito</span>
                </div>
                <div class="flex-1 h-0.5 bg-green-500 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-600 text-white rounded-full">
                        <span class="text-sm font-medium">2</span>
                    </div>
                    <span class="ml-3 text-sm font-medium text-red-600">Información</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full">
                        <span class="text-sm font-medium">3</span>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-500">Confirmación</span>
                </div>
            </div>
        </div>

        <form action="{{ route('store.checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información del Cliente
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre Completo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           placeholder="Ingresa tu nombre completo" required>
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           placeholder="Ej: +57 300 123 4567" required>
                                    @error('customer_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                       placeholder="tu@email.com" required>
                                @error('customer_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Method -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Método de Entrega
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <input type="radio" id="delivery" name="delivery_method" value="delivery" class="sr-only" checked>
                                    <label for="delivery" class="delivery-option flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">Domicilio</span>
                                        <span class="text-sm text-gray-500">$5,000</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="pickup" name="delivery_method" value="pickup" class="sr-only">
                                    <label for="pickup" class="delivery-option flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">Recoger</span>
                                        <span class="text-sm text-gray-500">Gratis</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="dine_in" name="delivery_method" value="dine_in" class="sr-only">
                                    <label for="dine_in" class="delivery-option flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">Mesa</span>
                                        <span class="text-sm text-gray-500">En local</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div id="delivery-address" class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Dirección de Entrega
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="franchise_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Franquicia <span class="text-red-500">*</span>
                                    </label>
                                    <select id="franchise_id" name="franchise_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
                                        <option value="">Selecciona una franquicia</option>
                                        @foreach($franchises as $franchise)
                                            <option value="{{ $franchise->id }}" {{ old('franchise_id') == $franchise->id ? 'selected' : '' }}>
                                                {{ $franchise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('franchise_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Zona
                                    </label>
                                    <select id="zone_id" name="zone_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                        <option value="">Selecciona una zona</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección Completa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="delivery_address" name="delivery_address" value="{{ old('delivery_address') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                       placeholder="Ej: Calle 123 #45-67, Apto 801" required>
                                @error('delivery_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ciudad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="delivery_city" name="delivery_city" value="{{ old('delivery_city') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           placeholder="Bogotá" required>
                                    @error('delivery_city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="delivery_state" class="block text-sm font-medium text-gray-700 mb-2">
                                        Departamento
                                    </label>
                                    <input type="text" id="delivery_state" name="delivery_state" value="{{ old('delivery_state') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           placeholder="Cundinamarca">
                                </div>
                                <div>
                                    <label for="delivery_country" class="block text-sm font-medium text-gray-700 mb-2">
                                        País <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="delivery_country" name="delivery_country" value="{{ old('delivery_country', 'Colombia') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           required>
                                </div>
                            </div>
                            <div>
                                <label for="delivery_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Instrucciones de Entrega
                                </label>
                                <textarea id="delivery_instructions" name="delivery_instructions" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                          placeholder="Ej: Tocar el timbre, casa de color azul...">{{ old('delivery_instructions') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Método de Pago
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" id="cash" name="payment_method" value="cash" class="sr-only" checked>
                                    <label for="cash" class="payment-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-gray-900">Efectivo</div>
                                            <div class="text-sm text-gray-500">Pago contra entrega</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="card" name="payment_method" value="card" class="sr-only">
                                    <label for="card" class="payment-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-gray-900">Tarjeta</div>
                                            <div class="text-sm text-gray-500">Débito o crédito</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="transfer" name="payment_method" value="transfer" class="sr-only">
                                    <label for="transfer" class="payment-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m3 0H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V5a1 1 0 00-1-1z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-gray-900">Transferencia</div>
                                            <div class="text-sm text-gray-500">Bancaria</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="wompi" name="payment_method" value="wompi" class="sr-only">
                                    <label for="wompi" class="payment-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-gray-900">Wompi</div>
                                            <div class="text-sm text-gray-500">Pago online</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Notas Adicionales
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentarios del Pedido
                                </label>
                                <textarea id="customer_notes" name="customer_notes" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                          placeholder="Alguna nota especial para tu pedido...">{{ old('customer_notes') }}</textarea>
                            </div>
                            <div>
                                <label for="tip_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Propina (Opcional)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" id="tip_amount" name="tip_amount" value="{{ old('tip_amount') }}" 
                                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                                           placeholder="0" min="0" step="1000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-4">
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

                        <!-- Order Items -->
                        <div class="px-6 py-4 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3 py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 class="w-12 h-12 object-cover rounded-lg" 
                                                 alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($item->product_name, 30) }}</h4>
                                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        ${{ number_format($item->total_price, 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary Details -->
                        <div class="px-6 py-4 space-y-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900">${{ number_format($cartTotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-semibold text-gray-900" id="shipping-cost">$5,000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">IVA (19%)</span>
                                <span class="font-semibold text-gray-900">${{ number_format($cartTotal * 0.19, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center" id="tip-row" style="display: none;">
                                <span class="text-gray-600">Propina</span>
                                <span class="font-semibold text-gray-900" id="tip-display">$0</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-green-600" id="order-total">${{ number_format($cartTotal * 1.19 + 5000, 0) }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Confirmar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryMethods = document.querySelectorAll('input[name="delivery_method"]');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const deliveryAddress = document.getElementById('delivery-address');
    const shippingCost = document.getElementById('shipping-cost');
    const orderTotal = document.getElementById('order-total');
    const tipInput = document.getElementById('tip_amount');
    const tipDisplay = document.getElementById('tip-display');
    const tipRow = document.getElementById('tip-row');
    
    const baseTotal = {{ $cartTotal * 1.19 }};
    
    // Handle delivery method changes
    deliveryMethods.forEach(method => {
        method.addEventListener('change', function() {
            updateDeliveryOption(this.value);
            updateTotal();
        });
    });
    
    // Handle payment method changes
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            updatePaymentOption(this);
        });
    });
    
    // Handle tip changes
    tipInput.addEventListener('input', function() {
        updateTip();
        updateTotal();
    });
    
    function updateDeliveryOption(method) {
        // Update visual state
        document.querySelectorAll('.delivery-option').forEach(option => {
            option.classList.remove('border-red-500', 'bg-red-50');
            option.classList.add('border-gray-300');
        });
        
        const selectedOption = document.querySelector(`label[for="${method}"]`);
        selectedOption.classList.remove('border-gray-300');
        selectedOption.classList.add('border-red-500', 'bg-red-50');
        
        // Show/hide delivery address
        if (method === 'delivery') {
            deliveryAddress.style.display = 'block';
            shippingCost.textContent = '$5,000';
        } else {
            deliveryAddress.style.display = 'none';
            shippingCost.textContent = '$0';
        }
    }
    
    function updatePaymentOption(selectedMethod) {
        // Update visual state
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('border-red-500', 'bg-red-50');
            option.classList.add('border-gray-300');
        });
        
        const selectedOption = selectedMethod.closest('.payment-option');
        selectedOption.classList.remove('border-gray-300');
        selectedOption.classList.add('border-red-500', 'bg-red-50');
    }
    
    function updateTip() {
        const tipAmount = parseInt(tipInput.value) || 0;
        tipDisplay.textContent = `$${tipAmount.toLocaleString()}`;
        
        if (tipAmount > 0) {
            tipRow.style.display = 'flex';
        } else {
            tipRow.style.display = 'none';
        }
    }
    
    function updateTotal() {
        const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked').value;
        const shippingAmount = deliveryMethod === 'delivery' ? 5000 : 0;
        const tipAmount = parseInt(tipInput.value) || 0;
        
        const total = baseTotal + shippingAmount + tipAmount;
        orderTotal.textContent = `$${total.toLocaleString()}`;
    }
    
    // Initialize
    updateDeliveryOption('delivery');
    updatePaymentOption(document.querySelector('input[name="payment_method"]:checked'));
    updateTotal();
});
</script>
@endpush
@endsection 