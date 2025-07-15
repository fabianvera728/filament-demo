@extends('layouts.store')

@section('title', $product->name . ' - Domisoft')

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
            <li>
                <a href="{{ route('store.products.index') }}" class="text-gray-500 hover:text-red-600 transition-colors">
                    Productos
                </a>
            </li>
            @if($product->category)
            <li class="text-gray-400">/</li>
            <li>
                <a href="{{ route('store.products.category', $product->category) }}" class="text-gray-500 hover:text-red-600 transition-colors">
                    {{ $product->category->name }}
                </a>
            </li>
            @endif
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                @if($product->primary_image_url)
                    <img id="main-image" 
                         src="{{ $product->primary_image_url }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product->name }}"
                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center\'><svg class=\'w-32 h-32 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if(count($product->all_images) > 1)
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->all_images as $index => $image)
                    <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 cursor-pointer hover:opacity-75 transition-opacity border-2 border-transparent hover:border-red-600 {{ $index === 0 ? 'border-red-600' : '' }}" 
                         onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                        <img src="{{ asset('storage/' . $image) }}" 
                             class="w-full h-full object-cover" 
                             alt="{{ $product->name }} - Imagen {{ $index + 1 }}"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Information -->
        <div class="space-y-6">
            <!-- Product Header -->
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    @if($product->category)
                        <span class="badge badge-secondary">{{ $product->category->name }}</span>
                    @endif
                    @if($product->stock_quantity > 0)
                        <span class="badge badge-success">Disponible</span>
                    @else
                        <span class="badge badge-danger">Agotado</span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                @if($product->sku)
                    <p class="text-gray-500 text-sm">SKU: {{ $product->sku }}</p>
                @endif
            </div>

            <!-- Price -->
            <div class="flex items-baseline space-x-3">
                <span class="text-4xl font-bold text-red-600">${{ number_format($product->price, 0) }}</span>
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-2xl text-gray-500 line-through">${{ number_format($product->compare_price, 0) }}</span>
                    <span class="badge badge-danger">
                        {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                    </span>
                @endif
            </div>

            <!-- Description -->
            @if($product->description)
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>
            @endif

            <!-- Product Details -->
            @if($product->weight || $product->dimensions || $product->material)
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles del Producto</h3>
                <dl class="grid grid-cols-1 gap-3">
                    @if($product->weight)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Peso:</dt>
                        <dd class="text-gray-900 font-medium">{{ $product->weight }}</dd>
                    </div>
                    @endif
                    @if($product->dimensions)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Dimensiones:</dt>
                        <dd class="text-gray-900 font-medium">{{ $product->dimensions }}</dd>
                    </div>
                    @endif
                    @if($product->material)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Material:</dt>
                        <dd class="text-gray-900 font-medium">{{ $product->material }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Stock Info -->
            <div class="flex items-center space-x-4 text-sm">
                @if($product->stock_quantity > 0)
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        @if($product->stock_quantity <= 5)
                            <span>¡Solo quedan {{ $product->stock_quantity }} unidades!</span>
                        @else
                            <span>En stock ({{ $product->stock_quantity }} disponibles)</span>
                        @endif
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Producto agotado</span>
                    </div>
                @endif
            </div>

            <!-- Add to Cart Form -->
            @if($product->stock_quantity > 0)
            <form action="{{ route('store.cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Quantity Selector -->
                <div class="flex items-center space-x-4">
                    <label for="quantity" class="text-sm font-medium text-gray-700">Cantidad:</label>
                    <div class="flex items-center border border-gray-300 rounded-md">
                        <button type="button" onclick="decreaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">-</button>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $product->stock_quantity }}" 
                               class="w-16 px-3 py-2 text-center border-0 focus:ring-0 focus:outline-none">
                        <button type="button" onclick="increaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition-colors">+</button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button type="submit" class="btn-primary w-full flex items-center justify-center space-x-2 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"/>
                    </svg>
                    <span>Agregar al Carrito</span>
                </button>
            </form>
            @else
            <div class="p-4 bg-gray-100 rounded-lg text-center">
                <p class="text-gray-600 mb-2">Producto actualmente agotado</p>
                <button class="btn-outline" disabled>No Disponible</button>
            </div>
            @endif

            <!-- Social Share -->
            <div class="border-t border-gray-200 pt-6">
                <p class="text-sm font-medium text-gray-900 mb-3">Compartir:</p>
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}" 
                       target="_blank" 
                       class="p-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="border-t border-gray-200 pt-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Productos Relacionados</h2>
            @if($product->category)
            <a href="{{ route('store.products.category', $product->category) }}" 
               class="text-red-600 hover:text-red-700 font-medium transition-colors">
                Ver todos en {{ $product->category->name }}
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="card product-card group">
                <div class="relative overflow-hidden">
                    @if($relatedProduct->primary_image_url)
                        <img src="{{ $relatedProduct->primary_image_url }}" 
                             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500" 
                             alt="{{ $relatedProduct->name }}"
                             onerror="this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-red-600">${{ number_format($relatedProduct->price, 0) }}</span>
                        @if($relatedProduct->stock_quantity > 0)
                            <span class="badge badge-success text-xs">Disponible</span>
                        @else
                            <span class="badge badge-danger text-xs">Agotado</span>
                        @endif
                    </div>
                    <a href="{{ route('store.products.show', $relatedProduct) }}" 
                       class="btn-outline w-full mt-4 text-center">
                        Ver Producto
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}
</style>
@endpush

<script>
function changeMainImage(imageSrc, thumbnailElement) {
    document.getElementById('main-image').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.aspect-square.cursor-pointer').forEach(thumb => {
        thumb.classList.remove('border-red-600');
        thumb.classList.add('border-transparent');
    });
    thumbnailElement.classList.remove('border-transparent');
    thumbnailElement.classList.add('border-red-600');
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.min);
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
    }
}

// Add to cart with AJAX (opcional)
document.querySelector('form[action="{{ route('store.cart.add') }}"]')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar contador del carrito
            if (data.cart_count) {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                    cartCount.classList.remove('hidden');
                }
            }
            
            // Mostrar mensaje de éxito
            showNotification('Producto agregado al carrito', 'success');
        } else {
            showNotification(data.message || 'Error al agregar el producto', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar el producto', 'error');
    });
});

function showNotification(message, type) {
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection 