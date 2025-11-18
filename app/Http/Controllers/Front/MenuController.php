<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()
            ->orderBy('order')
            ->get();

        // Get selected category from query parameter or default to 'Sandwiches'
        $firstCategory = Category::active()->first();
        $selectedCategory = $request->query('category', $firstCategory->name);

        $category = Category::where('name', $selectedCategory)
            ->active()
            ->first();
        $products = [];
        if ($category) {
            $products = $category->products()
                ->active()
                ->orderBy('order')
                ->get();
        }
        $setting = Setting::all()->keyBy('key');
        $sessionId = Session::getId();
        $cart = Cart::where('session_id', $sessionId)->first();
        $cartCount = !empty($cart)?$cart->items()->count():0;

        return view('menu.index', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'products' => $products,
            'cartCount' => $cartCount,
            'setting' => $setting
        ]);
    }

    public function show($id)
    {
        $item = Product::with('category')->findOrFail($id);
        $setting = Setting::all()->keyBy('key');
        return view('menu.show', compact('item','setting'));
    }
}
