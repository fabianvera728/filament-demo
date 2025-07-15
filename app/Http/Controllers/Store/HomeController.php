<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener productos destacados (puedes agregar un campo 'featured' a la tabla products)
        $featuredProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->take(8)
            ->get();

        // Obtener categorías activas
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->take(6)
            ->get();

        // Obtener productos más vendidos (simulado por ahora)
        $popularProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->take(6)
            ->get();

        // Obtener franquicias disponibles
        $franchises = Franchise::where('is_active', true)->get();

        return view('store.home', compact(
            'featuredProducts',
            'categories',
            'popularProducts',
            'franchises'
        ));
    }
}
