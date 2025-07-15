<!-- Enhanced Categories Section -->
<section id="categorias" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-semibold">
                    Categorías populares
                </span>
            </div>
            <h2 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Explora nuestras <span class="text-red-600 relative">
                    categorías
                    <svg class="absolute -bottom-2 left-0 w-full h-3 text-red-200" fill="currentColor" viewBox="0 0 100 10">
                        <path d="M0 8c30-6 70-6 100 0v2H0z"/>
                    </svg>
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Encuentra exactamente lo que buscas en nuestra amplia variedad de deliciosos platillos
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <a href="{{ route('store.products.category', $category) }}" 
                   class="group relative overflow-hidden rounded-3xl h-64 category-gradient hover:scale-105 transition-all duration-500 shadow-lg hover:shadow-2xl">
                    
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-300"></div>
                    
                    <!-- Content -->
                    <div class="relative h-full flex flex-col items-center justify-center text-white p-8 text-center">
                        <!-- Animated icon container -->
                        <div class="relative mb-6">
                            <div class="w-20 h-20 bg-white bg-gray-500/10 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-10 h-10 group-hover:rotate-12 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            
                            <!-- Floating decoration -->
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-300 rounded-full animate-bounce opacity-80"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-3 group-hover:scale-105 transition-transform duration-300">
                            {{ $category->name }}
                        </h3>
                        <div class="flex items-center space-x-2 opacity-90">
                            <span class="text-sm font-medium">{{ $category->products_count }} productos</span>
                            <div class="w-1 h-1 bg-white rounded-full"></div>
                            <span class="text-sm">Disponible ahora</span>
                        </div>
                        
                        <!-- Arrow indicator -->
                        <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section> 