<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['category'])
            ->where('stock_quantity', '>', 0);

        // Filtro por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtro por precio
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Búsqueda por nombre
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortOrder);
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Obtener categorías para el filtro
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        // Obtener rango de precios para filtros
        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('store.products.index', compact(
            'products',
            'categories',
            'priceRange'
        ));
    }

    public function show(Product $product)
    {
        // Cargar la relación category si no está cargada
        $product->load('category');
        
        if (!$product->is_active || $product->stock_quantity <= 0) {
            abort(404);
        }

        // Productos relacionados de la misma categoría
        $relatedProducts = Product::where('is_active', true)
            ->with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->take(4)
            ->get();

        return view('store.products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->where('stock_quantity', '>', 0)
            ->paginate(12);

        return view('store.products.category', compact('category', 'products'));
    }
}
