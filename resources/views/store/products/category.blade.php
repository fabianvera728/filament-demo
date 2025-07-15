@extends('layouts.store')

@section('title', $category->name . ' - Productos | Domisoft')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <a href="{{ route('store.products.index') }}" class="ml-1 md:ml-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">Productos</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 md:ml-2 text-sm font-medium text-gray-500">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                {{ $category->name }}
            </h1>
            @if($category->description)
                <p class="text-gray-600">{{ $category->description }}</p>
            @endif
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="badge badge-primary">
                {{ $products->total() }} productos
            </span>
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Results Info and Sort -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            class="p-2 bg-white text-gray-600 hover:bg-gray-50 transition-colors" 
                            id="list-view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                                    <a href="{{ route('store.products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                @if($product->category)
                                    <span class="badge badge-secondary ml-2 flex-shrink-0">{{ $product->category->name }}</span>
                                @endif
                            </div>

                            @if($product->description)
                                <p class="text-gray-600 text-sm mb-4 flex-grow leading-relaxed">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                            @endif

                            @if($product->sku)
                                <p class="text-xs text-gray-500 mb-3">SKU: {{ $product->sku }}</p>
                            @endif

                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-green-600">
                                    ${{ number_format($product->price, 0) }}
                                </span>
                                @if($product->stock_quantity > 0)
                                    <span class="text-sm text-green-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $product->stock_quantity }} disponibles
                                    </span>
                                @else
                                    <span class="text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Agotado
                                    </span>
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                <a href="{{ route('store.products.show', $product) }}" 
                                   class="btn-outline flex-1 text-center">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalles
                                </a>
                                @if($product->stock_quantity > 0)
                                    <button class="btn-success btn-add-to-cart p-3" data-product-id="{{ $product->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                </svg>
                <h3 class="text-2xl font-semibold text-gray-900 mb-3">No hay productos en esta categoría</h3>
                <p class="text-gray-600 mb-6">Aún no hemos agregado productos a la categoría "{{ $category->name }}".</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('store.products.index') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-3V6a4 4 0 00-8 0v1H5a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V9h1a1 1 0 100-2zM10 6a2 2 0 114 0v1h-4V6z"></path>
                        </svg>
                        Ver Todos los Productos
                    </a>
                    <a href="{{ route('store.home') }}" class="btn-outline">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const productsContainer = document.getElementById('products-container');
    const productItems = document.querySelectorAll('.product-item');

    // Grid view (default)
    gridView.addEventListener('click', function() {
        // Update button states
        gridView.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        gridView.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
        listView.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
        listView.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');

        // Update container layout
        productsContainer.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6';
        
        // Reset product cards to original grid layout
        productItems.forEach(item => {
            const card = item.querySelector('.product-card');
            card.className = 'card product-card group h-full';
            
            const cardContent = card.querySelector('.p-6');
            cardContent.className = 'p-6 flex flex-col flex-grow';
        });
    });

    // List view
    listView.addEventListener('click', function() {
        // Update button states
        listView.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        listView.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
        gridView.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
        gridView.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');

        // Update container layout
        productsContainer.className = 'space-y-6';
        
        // Update product cards to list layout
        productItems.forEach(item => {
            const card = item.querySelector('.product-card');
            card.className = 'card product-card group h-full flex flex-col md:flex-row';
            
            const cardContent = card.querySelector('.p-6');
            cardContent.className = 'p-6 flex flex-col flex-grow md:w-2/3';
            
            // Adjust image container for list view
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

    // Add to cart functionality
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            // Visual feedback
            const originalContent = this.innerHTML;
            this.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            this.disabled = true;
            
            // Simulate cart addition
            setTimeout(() => {
                this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                this.classList.remove('btn-success');
                this.classList.add('bg-green-600', 'hover:bg-green-700');
                
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.classList.add('btn-success');
                    this.classList.remove('bg-green-600', 'hover:bg-green-700');
                    this.disabled = false;
                }, 2000);
            }, 1000);
        });
    });
});
</script>
@endpush
@endsection 