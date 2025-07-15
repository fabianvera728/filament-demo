@extends('layouts.store')

@section('title', 'Productos - Domisoft')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="mb-8" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('store.home') }}" class="text-gray-500 hover:text-red-600 transition-colors">
                    Inicio
                </a>
            </li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">Productos</li>
        </ol>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Nuestros Productos</h1>
            <p class="text-gray-600">Descubre nuestra deliciosa variedad</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="text-gray-500">{{ $products->total() }} productos encontrados</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Filtros
                    </h2>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('store.products.index') }}" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nombre, descripción..." 
                                   class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                            <select name="category" class="input-field">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($priceRange && $priceRange->min_price && $priceRange->max_price)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rango de Precio</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}" 
                                       placeholder="Mín: ${{ number_format($priceRange->min_price, 0) }}" 
                                       min="0" 
                                       step="1000"
                                       class="input-field">
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}" 
                                       placeholder="Máx: ${{ number_format($priceRange->max_price, 0) }}" 
                                       min="0" 
                                       step="1000"
                                       class="input-field">
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                            <select name="sort" class="input-field">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <button type="submit" class="btn-primary w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Aplicar Filtros
                            </button>
                            <a href="{{ route('store.products.index') }}" class="btn-outline w-full inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Categorías Populares
                    </h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($categories->take(5) as $category)
                        <a href="{{ route('store.products.category', $category) }}" 
                           class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition-colors">
                            <span class="text-gray-700 hover:text-red-600">{{ $category->name }}</span>
                            <span class="badge badge-primary">{{ $category->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            @if($products->count() > 0)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <span class="text-gray-600 mb-4 sm:mb-0">
                        Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }} de {{ $products->total() }} productos
                    </span>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Ver:</span>
                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                            <button type="button" 
                                    class="p-2 bg-red-600 text-white hover:bg-red-700 transition-colors" 
                                    id="grid-view">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button type="button" 
                                    class="p-2 bg-white text-gray-600 hover:bg-gray-50 transition-colors" 
                                    id="list-view">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="products-container">
                    @foreach($products as $product)
                        <div class="product-item">
                            <div class="card product-card group h-full">
                                <div class="relative overflow-hidden">
                                    @php
                                        $imageUrl = null;
                                        if ($product->featured_image) {
                                            $imageUrl = asset('storage/' . $product->featured_image);
                                        } elseif ($product->images && count($product->images) > 0) {
                                            $imageUrl = asset('storage/' . $product->images[0]);
                                        }
                                    @endphp
                                    
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" 
                                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    @if($product->stock_quantity > 0)
                                        <div class="absolute top-3 right-3">
                                            <span class="badge badge-success">Disponible</span>
                                        </div>
                                    @else
                                        <div class="absolute top-3 right-3">
                                            <span class="badge badge-danger">Agotado</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                        @if($product->category)
                                            <span class="badge badge-secondary ml-2 flex-shrink-0">{{ $product->category->name }}</span>
                                        @endif
                                    </div>

                                    <p class="text-gray-600 text-sm mb-4 flex-grow leading-relaxed">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>

                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-2xl font-bold text-green-600">
                                            ${{ number_format($product->price, 0) }}
                                        </span>
                                        @if($product->stock_quantity > 0)
                                            <span class="text-sm text-green-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $product->stock_quantity }} disponibles
                                            </span>
                                        @else
                                            <span class="text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Agotado
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex space-x-3">
                                        <a href="{{ route('store.products.show', $product) }}" 
                                           class="btn-outline flex-1 text-center">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver Detalles
                                        </a>
                                        @if($product->stock_quantity > 0)
                                            <button class="btn-success btn-add-to-cart p-3" data-product-id="{{ $product->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-center mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-3">No se encontraron productos</h3>
                        <p class="text-gray-600 mb-6">Intenta ajustar tus filtros de búsqueda o explora nuestras categorías</p>
                        <a href="{{ route('store.products.index') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Ver todos los productos
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const productsContainer = document.getElementById('products-container');
    const productItems = document.querySelectorAll('.product-item');

    gridView.addEventListener('click', function() {
        gridView.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        gridView.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
        listView.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
        listView.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');

        productsContainer.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6';
        
        productItems.forEach(item => {
            const card = item.querySelector('.product-card');
            card.className = 'card product-card group h-full';
            
            const cardContent = card.querySelector('.p-6');
            cardContent.className = 'p-6 flex flex-col flex-grow';
        });
    });

    listView.addEventListener('click', function() {
        listView.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        listView.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
        gridView.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
        gridView.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');

        productsContainer.className = 'space-y-6';
        
        productItems.forEach(item => {
            const card = item.querySelector('.product-card');
            card.className = 'card product-card group h-full flex flex-col md:flex-row';
            
            const cardContent = card.querySelector('.p-6');
            cardContent.className = 'p-6 flex flex-col flex-grow md:w-2/3';
            
            const imageContainer = card.querySelector('.relative');
            if (imageContainer) {
                const img = imageContainer.querySelector('img, div');
                if (img) {
                    img.classList.remove('h-64');
                    img.classList.add('h-48', 'md:h-full', 'md:w-full');
                }
            }
        });
    });

    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            this.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
            
            setTimeout(() => {
                this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                this.classList.remove('btn-success');
                this.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 1000);
        });
    });
});
</script>
@endpush
@endsection 