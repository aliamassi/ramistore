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
            ]);
        }

        return $cart;
    }

    public function index($name)
    {
        $cart     = $this->getOrCreateCart();
        // eager-load product, customizationOption, and variant
        $cartItems = $cart->items()
            ->with(['product', 'customizationOption', 'variant'])
            ->get();

        $setting = Setting::all()->keyBy('key');

        return view('cart.index', [
            'cart'      => $cart,
            'cartItems' => $cartItems,
            'setting'   => $setting,
            'name'   => $name,
        ]);
    }

    public function add(Request $request,$name)
    {
        $request->validate([
            'product_id'             => 'required|exists:products,id',
            // for simple/customizable items
            'customization_option_id'=> 'nullable|exists:customization_options,id',
            // for variant items (we’ll validate it against the product’s variants)
            'variant_id'             => 'nullable|integer',
            'quantity'               => 'integer|min:1',
            'comments'               => 'nullable|string|max:1000',
        ]);

        $cart    = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);

        $quantity = $request->quantity ?? 1;
        $unitPrice = $product->price;     // default simple price
        $customizationName   = null;
        $customizationDetail = null;
        $customizationOption = null;
        $variant             = null;

        /*
         * 1) VARIANT FLOW (product->variants)
         */
        if ($request->filled('variant_id')) {
            // make sure this variant belongs to this product
            $variant = $product->variants()
                ->where('id', $request->variant_id)
                ->firstOrFail();

            $unitPrice          = $variant->price;
            $customizationName  = $variant->name;        // e.g. "Small"
            $customizationDetail= $request->comments;    // comments from form

            /*
             * 2) OLD CUSTOMIZATION FLOW (simple product with customization options)
             */
        } elseif ($request->filled('customization_option_id')) {
            $customizationOption = CustomizationOption::findOrFail($request->customization_option_id);
            $customizationName   = $customizationOption->name;
            $customizationDetail = $customizationOption->detail;
            $unitPrice           = $product->price + ($customizationOption->price_modifier ?? 0);

            /*
             * 3) PLAIN SIMPLE PRODUCT (no variant, no customization)
             *    -> leaves $unitPrice = $product->price
             */
        }

        // Check if item with same product/variant/customization already exists in cart
        $existingItemQuery = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant?->id)
            ->where('customization_option_id', $request->customization_option_id);

        $existingItem = $existingItemQuery->first();

        if ($existingItem) {
            // just increase quantity
            $existingItem->quantity += $quantity;
            $existingItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id'                => $cart->id,
                'product_id'             => $product->id,
                'variant_id'             => $variant?->id,
                'customization_option_id'=> $request->customization_option_id,
                'quantity'               => $quantity,
                'price'                  => $unitPrice,
                'customization_name'     => $customizationName,
                'customization_detail'   => $customizationDetail,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Item added to cart successfully',
                'cart_count' => $cart->fresh()->total_items,
            ]);
        }

        return redirect()
            ->route('cart.index',['name'=>$name])
            ->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Cart updated successfully',
                'subtotal'   => $cartItem->subtotal,
                'cart_total' => $cartItem->cart->total,
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart     = $cartItem->cart;
        $cartItem->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Item removed from cart',
                'cart_count' => $cart->fresh()->total_items,
                'cart_total' => $cart->fresh()->total,
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart cleared!');
    }

    public function count()
    {
        $cart = $this->getOrCreateCart();

        return response()->json([
            'count' => $cart->total_items,
        ]);
    }
}
