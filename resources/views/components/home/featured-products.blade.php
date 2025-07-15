<!-- Enhanced Featured Products -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold">
                    ⭐ Favoritos del chef
                </span>
            </div>
            <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Productos <span class="text-red-600 relative">
                    destacados
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-red-200" fill="currentColor" viewBox="0 0 100 10">
                        <path d="M0 8c30-6 70-6 100 0v2H0z"/>
                    </svg>
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Los favoritos de nuestros clientes seleccionados especialmente para ti por nuestros chefs
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                <div class="card product-card group hover:shadow-2xl transition-all duration-500">
                    <div class="relative overflow-hidden rounded-t-xl">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700" 
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center group-hover:from-gray-300 group-hover:to-gray-400 transition-colors duration-500">
                                <svg class="w-16 h-16 text-gray-400 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Enhanced status badges -->
                        <div class="absolute top-4 right-4 flex flex-col space-y-2">
                            @if($product->stock_quantity > 0)
                                <span class="badge badge-success shadow-lg backdrop-blur-sm bg-green-500 text-white">
                                    ✓ Disponible
                                </span>
                            @else
                                <span class="badge badge-danger shadow-lg backdrop-blur-sm bg-red-500 text-white">
                                    ✗ Agotado
                                </span>
                            @endif
                            
                            @if($loop->index < 2)
                                <span class="badge bg-yellow-400 text-gray-900 shadow-lg backdrop-blur-sm">
                                    🔥 Popular
                                </span>
                            @endif
                        </div>
                        
                        <!-- Wishlist button -->
                        <div class="absolute top-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="w-10 h-10 bg-white bg-gray-500/10 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition-colors duration-300">
                                {{ $product->name }}
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                        </div>
                        
                        <!-- Rating display -->
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">(4.5)</span>
                            <span class="text-xs text-gray-400">• 127 reseñas</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 0) }}</span>
                                @if($product->stock_quantity > 0)
                                    <span class="text-sm text-green-600 flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                        En stock ({{ $product->stock_quantity }})
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Quick view button -->
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-16">
            <a href="{{ route('store.products.index') }}" 
               class="group btn-primary inline-flex items-center px-10 py-4 text-lg font-semibold hover:shadow-xl transition-all duration-300">
                <span>Ver todos los productos</span>
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section> 