<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of sliders.
     */
    public function index()
    {
        $sliders = Slider::ordered()->get()->map(function($slider) {
            return [
                'id' => $slider->id,
                'title' => $slider->title,
                'image_path' => $slider->image_path,
                'image_url' => $slider->image_url,
                'order' => $slider->order,
                'is_active' => $slider->is_active,
                'created_at' => $slider->created_at,
                'updated_at' => $slider->updated_at,
            ];
        });

        return response()->json($sliders);
    }

    /**
     * Store a newly created slider.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('sliders', 'public');

            // Get the highest order value and add 1
            $maxOrder = Slider::max('order') ?? -1;

            $slider = Slider::create([
                'title' => $request->input('title'),
                'image_path' => $path,
                'order' => $maxOrder + 1,
                'is_active' => $request->input('is_active', true),
            ]);

            return response()->json([
                'message' => 'Slider created successfully',
                'slider' => [
                    'id' => $slider->id,
                    'title' => $slider->title,
                    'image_path' => $slider->image_path,
                    'image_url' => $slider->image_url,
                    'order' => $slider->order,
                    'is_active' => $slider->is_active,
                ]
            ], 201);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    /**
     * Update the specified slider.
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
                Storage::disk('public')->delete($slider->image_path);
            }

            $image = $request->file('image');
            $path = $image->store('sliders', 'public');
            $slider->image_path = $path;
        }

        // Update other fields
        if ($request->has('title')) {
            $slider->title = $request->input('title');
        }

        if ($request->has('is_active')) {
            $slider->is_active = $request->input('is_active');
        }

        $slider->save();

        return response()->json([
            'message' => 'Slider updated successfully',
            'slider' => [
                'id' => $slider->id,
                'title' => $slider->title,
                'image_path' => $slider->image_path,
                'image_url' => $slider->image_url,
                'order' => $slider->order,
                'is_active' => $slider->is_active,
            ]
        ]);
    }

    /**
     * Remove the specified slider.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Delete image file
        if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
            Storage::disk('public')->delete($slider->image_path);
        }

        $slider->delete();

        return response()->json(['message' => 'Slider deleted successfully']);
    }

    /**
     * Update the order of sliders.
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sliders' => 'required|array',
            'sliders.*.id' => 'required|exists:sliders,id',
            'sliders.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->input('sliders') as $sliderData) {
            Slider::where('id', $sliderData['id'])
                ->update(['order' => $sliderData['order']]);
        }

        return response()->json(['message' => 'Slider order updated successfully']);
    }
}
