<!-- Enhanced Testimonials Carousel -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold">
                    💬 Testimonios
                </span>
            </div>
            <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Lo que dicen nuestros <span class="text-red-600 relative">
                    clientes
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-red-200" fill="currentColor" viewBox="0 0 100 10">
                        <path d="M0 8c30-6 70-6 100 0v2H0z"/>
                    </svg>
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Experiencias reales de clientes que confían en nuestro servicio día a día
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="testimonial-card p-8 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400 transform group-hover:scale-110 transition-transform duration-300" style="animation-delay: {{ $i * 0.1 }}s" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    @endfor
                </div>
                <div class="relative">
                    <svg class="absolute top-0 left-0 w-8 h-8 text-blue-200 -translate-x-2 -translate-y-2" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M10 8c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2H8c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2h-2c0-2.2 1.8-4 4-4V8z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 italic text-lg leading-relaxed pl-6">
                        "La comida llegó súper rápido y caliente. El servicio al cliente es excelente, definitivamente volveré a pedir."
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4 group-hover:scale-110 transition-transform duration-300">
                        M
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">María González</div>
                        <div class="text-gray-500 text-sm flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            Cliente frecuente
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card p-8 bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400 transform group-hover:scale-110 transition-transform duration-300" style="animation-delay: {{ $i * 0.1 }}s" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    @endfor
                </div>
                <div class="relative">
                    <svg class="absolute top-0 left-0 w-8 h-8 text-green-200 -translate-x-2 -translate-y-2" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M10 8c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2H8c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2h-2c0-2.2 1.8-4 4-4V8z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 italic text-lg leading-relaxed pl-6">
                        "Increíble variedad de restaurantes y la app es muy fácil de usar. Los precios son muy competitivos."
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4 group-hover:scale-110 transition-transform duration-300">
                        C
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Carlos Rodríguez</div>
                        <div class="text-gray-500 text-sm flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            Usuario desde 2023
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card p-8 bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400 transform group-hover:scale-110 transition-transform duration-300" style="animation-delay: {{ $i * 0.1 }}s" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    @endfor
                </div>
                <div class="relative">
                    <svg class="absolute top-0 left-0 w-8 h-8 text-purple-200 -translate-x-2 -translate-y-2" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M10 8c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2H8c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v8c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2h-2c0-2.2 1.8-4 4-4V8z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 italic text-lg leading-relaxed pl-6">
                        "El mejor servicio de delivery que he usado. Siempre puntuales y la comida llega en perfectas condiciones."
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-4 group-hover:scale-110 transition-transform duration-300">
                        A
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Ana López</div>
                        <div class="text-gray-500 text-sm flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            Fan de Domisoft
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trust indicators -->
        <div class="text-center mt-16">
            <div class="inline-flex items-center space-x-8 bg-gray-50 rounded-2xl px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">4.9/5</div>
                        <div class="text-sm text-gray-600">Calificación promedio</div>
                    </div>
                </div>
                
                <div class="w-px h-12 bg-gray-300"></div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-400 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">1,200+</div>
                        <div class="text-sm text-gray-600">Reseñas verificadas</div>
                    </div>
                </div>
                
                <div class="w-px h-12 bg-gray-300"></div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">98%</div>
                        <div class="text-sm text-gray-600">Satisfacción del cliente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 