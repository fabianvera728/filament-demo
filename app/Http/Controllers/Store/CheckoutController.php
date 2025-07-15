<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Franchise;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('store.cart.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $cartTotal = $this->getCartTotal();
        $franchises = Franchise::where('is_active', true)->get();
        $zones = Zone::where('is_active', true)->get();

        return view('store.checkout.index', compact(
            'cartItems',
            'cartTotal',
            'franchises',
            'zones'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:100',
            'delivery_state' => 'nullable|string|max:100',
            'delivery_postal_code' => 'nullable|string|max:20',
            'delivery_country' => 'required|string|max:100',
            'delivery_instructions' => 'nullable|string',
            'franchise_id' => 'required|exists:franchises,id',
            'zone_id' => 'nullable|exists:zones,id',
            'delivery_method' => 'required|in:delivery,pickup,dine_in',
            'payment_method' => 'required|in:cash,card,transfer,wompi,openpay,globalpay,wenjoy',
            'customer_notes' => 'nullable|string',
            'tip_amount' => 'nullable|numeric|min:0',
        ]);

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('store.cart.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $subtotal = $this->getCartTotal();
        $taxAmount = $subtotal * 0.19; // IVA del 19%
        $shippingAmount = $request->delivery_method === 'delivery' ? 5000 : 0; // $5000 de domicilio
        $tipAmount = $request->tip_amount ?? 0;
        $totalAmount = $subtotal + $taxAmount + $shippingAmount + $tipAmount;

        try {
            DB::beginTransaction();

            // Crear el pedido
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'franchise_id' => $request->franchise_id,
                'zone_id' => $request->zone_id,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'tip_amount' => $tipAmount,
                'total_amount' => $totalAmount,
                'currency' => 'COP',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_notes' => $request->customer_notes,
                'delivery_address' => $request->delivery_address,
                'delivery_city' => $request->delivery_city,
                'delivery_state' => $request->delivery_state,
                'delivery_postal_code' => $request->delivery_postal_code,
                'delivery_country' => $request->delivery_country,
                'delivery_instructions' => $request->delivery_instructions,
                'delivery_method' => $request->delivery_method,
                'estimated_delivery_time' => now()->addMinutes(30), // 30 minutos estimados
            ]);

            // Crear los items del pedido
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product_name,
                    'product_sku' => $cartItem->product_sku,
                    'product_description' => $cartItem->product->description ?? '',
                    'unit_price' => $cartItem->product_price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'product_options' => $cartItem->product_options,
                    'product_image' => $cartItem->product->image ?? null,
                ]);

                // Reducir stock del producto
                $product = $cartItem->product;
                if ($product && $product->stock_quantity >= $cartItem->quantity) {
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Limpiar el carrito
            $this->clearCart();

            DB::commit();

            return redirect()->route('store.checkout.success', $order)
                ->with('success', 'Pedido creado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Verificar que el pedido pertenece al usuario actual (si está autenticado)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('store.checkout.success', compact('order'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        // Verificar que el pedido pertenece al usuario actual
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'franchise', 'zone']);

        return view('store.orders.show', compact('order'));
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

    private function clearCart()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        Cart::clearCart($sessionId, $userId);
    }
}
