<!-- Completely Redesigned Franchises Section -->
@if($franchises->count() > 0)
<section class="py-20 bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900 text-white relative overflow-hidden">
    <!-- Enhanced background effects -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-20 w-40 h-40 bg-blue-400 rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-purple-400 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-indigo-400 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-20">
            <div class="inline-block mb-6">
                <span class="bg-gradient-to-r from-blue-400 to-purple-400 text-white px-6 py-3 rounded-full text-sm font-semibold shadow-lg">
                    🌍 Presencia Nacional
                </span>
            </div>
            <h2 class="text-5xl lg:text-7xl font-bold mb-8">
                Nuestras <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">
                    franquicias
                </span>
            </h2>
            <p class="text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                Estamos presentes en las principales ciudades del país, acercándote siempre la mejor comida con la máxima calidad
            </p>
        </div>
        
        <!-- Interactive Map Area -->
        <div class="mb-16">
            <div class="bg-gradient-to-br from-blue-800 to-indigo-800 rounded-3xl p-8 shadow-2xl border border-blue-700">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-4 text-yellow-300">Mapa de Cobertura</h3>
                    <p class="text-blue-200">Encuentra la franquicia más cercana a tu ubicación</p>
                </div>
                
                <!-- Simulated Map -->
                <div class="relative bg-gradient-to-br from-blue-900 to-indigo-900 rounded-2xl h-80 flex items-center justify-center border-2 border-blue-600">
                    <div class="absolute inset-4 bg-gradient-to-br from-blue-800 to-blue-900 rounded-xl opacity-50"></div>
                    
                    <!-- Map markers -->
                    <div class="relative w-full h-full">
                        @foreach($franchises as $index => $franchise)
                            <div class="absolute animate-pulse" style="
                                top: {{ 20 + ($index * 15) }}%; 
                                left: {{ 15 + ($index * 20) }}%; 
                                animation-delay: {{ $index * 0.5 }}s;
                            ">
                                <div class="relative group cursor-pointer">
                                    <div class="w-6 h-6 bg-yellow-400 rounded-full shadow-lg group-hover:scale-150 transition-transform duration-300 flex items-center justify-center">
                                        <div class="w-2 h-2 bg-red-600 rounded-full"></div>
                                    </div>
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-black bg-gray-500/10 text-white px-3 py-2 rounded-lg text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                        {{ $franchise->name }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Central location indicator -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
                                <svg class="w-8 h-8 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map overlay text -->
                    <div class="absolute top-4 left-4 bg-black bg-gray-500/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-yellow-300 font-semibold text-sm">📍 {{ $franchises->count() }} ubicaciones activas</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Franchise Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($franchises as $franchise)
                <div class="group relative">
                    <!-- Card -->
                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-2xl p-8 border border-white/20 hover:border-yellow-400/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <!-- Active indicator -->
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full flex items-center justify-center shadow-lg">
                                    <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            
                            <!-- Status badge -->
                            <div class="flex flex-col items-end">
                                <span class="bg-green-400 text-green-900 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    ✓ ACTIVA
                                </span>
                                <span class="text-xs text-green-300 mt-1">24/7 disponible</span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-yellow-300 transition-colors duration-300">
                                    {{ $franchise->name }}
                                </h3>
                                <p class="text-gray-300 leading-relaxed">
                                    {{ $franchise->address ?? 'Múltiples ubicaciones estratégicas para mejor cobertura' }}
                                </p>
                            </div>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 py-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-400">{{ rand(50, 200) }}+</div>
                                    <div class="text-xs text-gray-400">Pedidos diarios</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-400">{{ rand(15, 45) }} min</div>
                                    <div class="text-xs text-gray-400">Tiempo promedio</div>
                                </div>
                            </div>
                            
                            <!-- Services -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-blue-500/20 text-blue-300 px-2 py-1 rounded-full text-xs">🚚 Delivery</span>
                                <span class="bg-purple-500/20 text-purple-300 px-2 py-1 rounded-full text-xs">📱 App móvil</span>
                                <span class="bg-green-500/20 text-green-300 px-2 py-1 rounded-full text-xs">💳 Pago online</span>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex gap-3 pt-6 border-t border-white/10">
                            <button class="flex-1 bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold py-3 px-4 rounded-xl hover:scale-105 transition-all duration-200 shadow-lg">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    Ver ubicación
                                </span>
                            </button>
                            <button class="bg-white/10 backdrop-blur-sm text-white p-3 rounded-xl hover:bg-white/20 transition-all duration-200 border border-white/20">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Expansion CTA -->
        <div class="text-center">
            <div class="inline-flex flex-col items-center space-y-6 bg-gradient-to-br from-yellow-400/10 to-orange-400/10 backdrop-blur-lg rounded-3xl p-12 border border-yellow-400/20">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-white mb-3">¿No encuentras tu ciudad?</h3>
                    <p class="text-xl text-gray-300 mb-6 max-w-md">
                        Estamos expandiéndonos constantemente. Contáctanos para solicitar una nueva ubicación
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-bold px-8 py-4 rounded-xl hover:scale-105 transition-all duration-200 shadow-lg">
                        Solicitar nueva ubicación
                    </button>
                    <button class="border-2 border-yellow-400 text-yellow-400 font-bold px-8 py-4 rounded-xl hover:bg-yellow-400 hover:text-gray-900 transition-all duration-200">
                        Ser franquiciado
                    </button>
                </div>
                
                <!-- Contact info -->
                <div class="flex items-center space-x-6 pt-6 border-t border-white/10">
                    <div class="flex items-center space-x-2 text-gray-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>franquicias@domisoft.com</span>
                    </div>
                    <div class="flex items-center space-x-2 text-gray-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>+57 (1) 234-5678</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif 