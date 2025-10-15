<?php

namespace App\Http\Controllers\Panel;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function uploadImage(Request $request)
    {
        $user = auth()->user();
        // This replaces the old one because the collection is singleFile()
        $media = $user
            ->addMediaFromRequest('logo')
            ->usingName('logo')
            ->toMediaCollection('logo');

        return response()->json([
            'status' => true,
            'id'  => $media->id,
            'url' => $user->getFirstMediaUrl('logo'),
            'message' => __('messages.created')
        ]);
    }
}
