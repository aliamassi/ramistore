<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $categories = $user->categories()->with('products')->latest()->paginate(20);
        return response()->json([
            'status' => true,
            'categories' => $categories
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
        Category::create($request->all());
        return response()->json([
            'status' => true,
            'message' => __('messages.created')
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
