<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\CustomizationOption;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function getOrCreateCart()
    {
        $sessionId = Session::getId();

        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id()
            ]);
        }

        return $cart;
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->items()->with(['product', 'customizationOption'])->get();
        $setting = Setting::all()->keyBy('key');
        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'setting' => $setting
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customization_option_id' => 'nullable|exists:customization_options,id',
            'quantity' => 'integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);
        $customizationOption = null;
        $customizationName = null;
        $customizationDetail = null;

        if ($request->customization_option_id) {
            $customizationOption = CustomizationOption::findOrFail($request->customization_option_id);
            $customizationName = $customizationOption->name;
            $customizationDetail = $customizationOption->detail;
        }

        // Check if item with same customization already exists in cart
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('customization_option_id', $request->customization_option_id)
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $request->quantity ?? 1;
            $existingItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'customization_option_id' => $request->customization_option_id,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->price + ($customizationOption->price_modifier ?? 0),
                'customization_name' => $customizationName,
                'customization_detail' => $customizationDetail
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart_count' => $cart->fresh()->total_items
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'subtotal' => $cartItem->subtotal,
                'cart_total' => $cartItem->cart->total
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;
        $cartItem->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cart->fresh()->total_items,
                'cart_total' => $cart->fresh()->total
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    public function count()
    {
        $cart = $this->getOrCreateCart();

        return response()->json([
            'count' => $cart->total_items
        ]);
    }
}
