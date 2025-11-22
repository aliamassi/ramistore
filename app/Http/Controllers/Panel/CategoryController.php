<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->admin();
        $categories = $user->categories()->with(['products' => function ($query) {
            $query->with('variants')->withCount('variants')
                ->orderBy('order');
        }])
            ->latest()
            ->get();
        return response()->json([
            'status' => true,
            'categories' => $categories,
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
        $request['admin_id'] = auth()->user()->id;
        $category = Category::create($request->all());
        $setting = Setting::all()->keyBy('key');
        return response()->json([
            'status' => true,
            'message' => __('messages.created'),
            'category' => $category,
            'setting' => $setting,
        ]);
    }
    public function reorder(Category $category,Request $request)
    {
        $request->validate([
            'product_ids' => 'required'
        ]);
         $product_ids = $request->product_ids;
         foreach ($product_ids as $order=> $product_id){
             Product::find($product_id)->update(['order'=>$order]);
         }

         $products = Product::with('variants')->orderBy('order')->whereIn('id',$product_ids)->get();
        return response()->json([
            'status' => true,
            'message' => __('messages.success'),
            'products' => $products
        ]);
    }
    public function changeVisibility($category, Request $request)
    {
        $request->validate([
            'action' => 'required',
            'id' => 'required'
        ]);
        $id = $request->id;
        switch ($request->action) {
            case "specific":
                $category = Category::find($id);
                $is_visible = $category->is_visible == 1 ? 0 : 1;
                $category->update(['is_visible' => $is_visible]);
                $category = Category::with('products')->find($id);
                break;
        }
        return response()->json([
            'status' => true,
            'category' => $category,
            'message' => __('messages.updated')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $category = Category::find($category);
        $category->update($request->all());
        return response()->json([
            'status' => true,
            'message' => __('messages.updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        $category = Category::find($category);
        try {
            $category->delete();
        } catch (\Exception $exc) {

        }
        return response()->json([
            'status' => true,
            'message' => __('messages.deleted')
        ]);
    }
}
