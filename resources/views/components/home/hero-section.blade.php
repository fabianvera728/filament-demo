<!-- Enhanced Hero Section with Particles -->
<section class="relative hero-gradient text-white overflow-hidden">
    <!-- Animated background particles -->
    <div class="absolute inset-0 opacity-20">
        <div class="particle" style="animation-delay: 0s; top: 20%; left: 10%;"></div>
        <div class="particle" style="animation-delay: 1s; top: 60%; left: 80%;"></div>
        <div class="particle" style="animation-delay: 2s; top: 80%; left: 20%;"></div>
        <div class="particle" style="animation-delay: 3s; top: 30%; left: 70%;"></div>
        <div class="particle" style="animation-delay: 4s; top: 70%; left: 50%;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 animate-fade-in-up">
                <div class="inline-block">
                    <span class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-semibold">
                        🚀 ¡Envío gratis en pedidos sobre $50.000!
                    </span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                    Deliciosa comida 
                    <span class="text-yellow-400 inline-block hover:scale-105 transition-transform cursor-default">
                        directo a tu puerta
                    </span>
                </h1>
                
                <p class="text-xl text-gray-100 max-w-lg leading-relaxed">
                    Descubre los mejores restaurantes y platillos de tu ciudad. 
                    <span class="text-yellow-300 font-semibold">Rápido, fácil y siempre fresco.</span>
                </p>
                
                <!-- Enhanced CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('store.products.index') }}" 
                       class="group btn-secondary inline-flex items-center justify-center bg-yellow-400 text-gray-900 hover:bg-yellow-300 px-8 py-4 text-lg font-semibold rounded-xl transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Ver Productos
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    
                    <a href="#estadisticas" 
                       class="group btn-outline inline-flex items-center justify-center border-2 border-white text-white hover:bg-white hover:text-gray-900 px-8 py-4 text-lg font-semibold rounded-xl transition-all hover:scale-105">
                        <svg class="w-5 h-5 mr-2 group-hover:bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m0 0l7-7" />
                        </svg>
                        Explorar
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="flex items-center space-x-6 pt-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-gray-900 font-bold text-sm border-2 border-white">5</div>
                            <div class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white">★</div>
                        </div>
                        <span class="text-gray-200 text-sm">+1000 clientes satisfechos</span>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Hero Image with 3D Effect -->
            <div class="relative text-center lg:text-right">
                <div class="relative inline-block">
                    <!-- Main hero graphic -->
                    <div class="floating-element bg-white bg-blue-500/10 backdrop-blur-sm rounded-3xl p-8 shadow-2xl">
                        <div class="relative">
                            <svg class="w-64 h-64 text-yellow-400 drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            
                            <!-- Floating elements around main graphic -->
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-green-400 rounded-full flex items-center justify-center animate-bounce-slow shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            
                            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-pink-400 rounded-full flex items-center justify-center animate-pulse shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 