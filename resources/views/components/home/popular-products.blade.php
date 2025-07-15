<!-- Enhanced Popular Products with Fixed Images -->
<section class="py-20 bg-gradient-to-br from-red-50 to-orange-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold">
                    🔥 Trending ahora
                </span>
            </div>
            <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Más <span class="text-red-600 relative">
                    populares
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-red-200" fill="currentColor" viewBox="0 0 100 10">
                        <path d="M0 8c30-6 70-6 100 0v2H0z"/>
                    </svg>
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Los productos más pedidos esta semana por nuestros clientes más exigentes
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularProducts as $product)
                <div class="card product-card group hover:shadow-2xl transition-all duration-500 bg-white">
                    <!-- Imagen completa arriba -->
                    <div class="relative overflow-hidden rounded-t-xl">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-700" 
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center group-hover:from-gray-300 group-hover:to-gray-400 transition-colors duration-500">
                                <svg class="w-16 h-16 text-gray-400 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Trending indicator -->
                        <div class="absolute top-4 right-4 w-12 h-12 bg-red-500 rounded-full flex items-center justify-center animate-pulse shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <!-- Popular badge -->
                        <div class="absolute top-4 left-4">
                            <span class="badge bg-yellow-400 text-gray-900 shadow-lg backdrop-blur-sm font-bold">
                                #{{ $loop->iteration }} Popular
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contenido debajo -->
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition-colors duration-300">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                            </div>
                            <div class="flex items-center ml-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1 font-medium">4.9</span>
                            </div>
                        </div>
                        
                        <!-- Precio y acciones -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 0) }}</span>
                                <span class="text-xs text-green-500 font-medium">+ envío gratis</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('store.products.show', $product) }}" 
                                   class="btn-outline px-4 py-2 text-sm font-medium hover:shadow-md transition-all duration-200">
                                    Ver
                                </a>
                                @if($product->stock_quantity > 0)
                                    <button class="btn-success btn-add-to-cart w-12 h-12 p-0 flex items-center justify-center rounded-full hover:shadow-lg transition-all duration-200" 
                                            data-product-id="{{ $product->id }}"
                                            data-original-text="+">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Stock indicator -->
                        @if($product->stock_quantity > 0)
                            <div class="flex items-center justify-center">
                                <span class="text-xs text-green-600 flex items-center bg-green-50 px-3 py-1 rounded-full">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                    Disponible ahora • {{ $product->stock_quantity }} en stock
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section> 