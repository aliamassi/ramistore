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
    public function index(Request $request, $name)
    {

        $setting = \App\Models\Setting::where('key', 'business_name')->where('value', $name)->first();
        if (empty($setting)) abort(404);
        $admin = Admin::find($setting->admin_id);
        $categories = $admin
            ->categories()
            ->latest()
            ->active()
            ->with(['products' => function ($query) {
                $query->with('variants')->withCount('variants')
                    ->orderBy('order');
            }])
            ->get();
        $restaurant = $admin->settings->keyBy('key');

        // Get selected category from query parameter or default to 'Sandwiches'
        $firstCategory = $admin->categories()->active()->first();
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
            'user' => $admin,
            'name' => $name,
            'setting' => $setting,
            'restaurant' => $restaurant
        ]);
    }

    public function show($name,$id)
    {
        $item = Product::with('category')->findOrFail($id);
        $setting = Setting::all()->keyBy('key');
        return view('menu.show', compact('name','item', 'setting'));
    }
}
