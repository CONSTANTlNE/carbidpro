<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:9048',
        ]);

        // Find the record
        $record = Car::find($request->car_id);

        if ($record && $request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Add each image to the media library
                $record->addMedia($image)->toMediaCollection('images');
            }
        }

        return response()->json(['message' => 'Images uploaded successfully!']);
    }


    public function storeBlImages(Request $request)
    {
        // Validate the request
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:9048',
        ]);

        // Find the record
        $record = Car::find($request->car_id);

        if ($record && $request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Add each image to the media library
                $record->addMedia($image)->toMediaCollection('bl_images');
            }
        }

        return response()->json(['message' => 'Images uploaded successfully!']);
    }
}
