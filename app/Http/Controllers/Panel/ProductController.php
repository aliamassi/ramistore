<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        Product::create($request->all());
        return response()->json([
            'status' => true,
            'message' => __('messages.created')
        ]);
    }

    public function uploadImage(Request $request)
    {

        $product = Product::find($request->product);
        if ($product) {
            foreach ($request->images as $image) {
                $product
                    ->addMedia($image)
                    ->toMediaCollection('products');
            }

        }

        return response()->json([
            'status' => true,
            'message' => __('messages.created')
        ]);
    }

    public function getImages(Request $request)
    {

        $product = Product::find($request->product);
        return new ProductResource($product);

    }

    /**
     * Update the specified resource in storage.
     */
    public function changeVisibility($product, Request $request)
    {
        $request->validate([
            'action' => 'required',
            'id' => 'required'
        ]);
        $id = $request->id;
        $category = [];
        switch ($request->action) {
            case "all":
                $category = Category::find($id);
                $category->products()->update(['is_visible' => 1]);
                $category = Category::with('products')->find($id);
                break;
            case"specific":
                $product = Product::find($id);
                $is_visible = $product->is_visible == 1?0:1;
                $product->update(['is_visible' => $is_visible]);
                $category = Category::with('products')->find($product->category_id);
                break;
        }
        return response()->json([
            'status' => true,
            'category' => $category,
            'message' => __('messages.updated')
        ]);
    }

    public function update($product, Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $product = Product::find($product);
        $product->update($request->all());
        return response()->json([
            'status' => true,
            'product' => $product,
            'message' => __('messages.updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (\Exception $exc) {

        }
        return response()->json([
            'status' => true,
            'message' => __('messages.deleted')
        ]);
    }
}
