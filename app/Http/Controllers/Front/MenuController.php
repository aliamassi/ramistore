<?php

namespace App\Http\Controllers\Front;

use App\Models\Admin;
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
        $user = null;
        if ($request->id) {
            $categories = Category::where('admin_id', $request->id)->active()->orderBy('order')->with('products.variants')->latest()->get();
            $user = Admin::find($request->id);
        } else {
            $categories = Category::active()
                ->orderBy('order')
                ->get();
        }

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
        $cartCount = !empty($cart) ? $cart->items()->count() : 0;

        return view('menu.index', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'products' => $products,
            'cartCount' => $cartCount,
            'user' => $user,
            'setting' => $setting
        ]);
    }

    public function show($id)
    {
        $item = Product::with('category')->findOrFail($id);
        $setting = Setting::all()->keyBy('key');
        return view('menu.show', compact('item', 'setting'));
    }
}
