<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $cartTotal = $this->getCartTotal();

        return view('store.cart.index', compact('cartItems', 'cartTotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_options' => 'nullable|array',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verificar stock disponible
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible'
            ], 400);
        }

        $sessionId = session()->getId();
        $userId = Auth::id();

        // Verificar si el producto ya está en el carrito
        $existingCartItem = Cart::where('product_id', $product->id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($existingCartItem) {
            // Actualizar cantidad existente
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficiente stock para agregar más cantidad'
                ], 400);
            }

            $existingCartItem->update([
                'quantity' => $newQuantity,
                'total_price' => $newQuantity * $product->price,
            ]);

            $cartItem = $existingCartItem;
        } else {
            // Crear nuevo item en el carrito
            $cartItem = Cart::create([
                'session_id' => $userId ? null : $sessionId,
                'user_id' => $userId,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'product_price' => $product->price,
                'quantity' => $request->quantity,
                'product_options' => $request->product_options,
                'total_price' => $request->quantity * $product->price,
            ]);
        }

        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'item' => $cartItem
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Verificar que el carrito pertenece al usuario/sesión actual
        if (!$this->canAccessCart($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $product = $cart->product;

        // Verificar stock
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible'
            ], 400);
        }

        $cart->update([
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $cart->product_price,
        ]);

        $cartTotal = $this->getCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado',
            'cart_total' => $cartTotal,
            'item_total' => $cart->total_price
        ]);
    }

    public function remove(Cart $cart)
    {
        // Verificar que el carrito pertenece al usuario/sesión actual
        if (!$this->canAccessCart($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $cart->delete();

        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal
        ]);
    }

    public function clear()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        Cart::clearCart($sessionId, $userId);

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    public function count()
    {
        $cartCount = $this->getCartCount();

        return response()->json([
            'count' => $cartCount
        ]);
    }

    private function getCartItems()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        return Cart::getCartItems($sessionId, $userId);
    }

    private function getCartTotal()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        return Cart::getCartTotal($sessionId, $userId);
    }

    private function getCartCount()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $query = Cart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->sum('quantity');
    }

    private function canAccessCart(Cart $cart): bool
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        if ($userId && $cart->user_id === $userId) {
            return true;
        }

        if (!$userId && $cart->session_id === $sessionId) {
            return true;
        }

        return false;
    }
}
