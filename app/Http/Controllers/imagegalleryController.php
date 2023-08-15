<?php

namespace App\Http\Controllers;

use App\Models\imagegallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class imagegalleryController extends Controller
{
    public function index()
    {
        $images = imagegallery::all();

        return response()->json([
            'images' => $images,
        ]);
    }

    public function store(Request $request)
    {
        // Check if the image field is missing
        if (!$request->hasFile('image')) {
            return response()->json([
                'message' => 'Required field is missing: image',
            ], 400);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If the validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the uploaded file
        $image = $request->file('image');

        // Store the image in the storage directory
        $imagePath = $image->store('uploads', 'public');

        // Get the full URL of the stored image
        $imageUrl = URL::asset('/storage/' . $imagePath);

        // Create a new image
        $image = imagegallery::create([
            'image_url' => $imageUrl,
        ]);

        // Return the JSON response with the created image
        return response()->json([
            'message' => 'Image uploaded successfully',
            'image' => $image,
        ], 201);
    }


    public function destroy($id)
    {
        // Find the image by its ID
        $image = imagegallery::find($id);

        // If the image doesn't exist, return a not found response
        if (!$image) {
            return response()->json([
                'message' => 'Image not found',
            ], 404);
        }

        // Get the image path
        $imagePath = 'public/uploads/' . basename($image->image_url);

        // Delete the image from the database
        $image->delete();

        // Delete the associated image from storage
        if ($image->image_url && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Return a success message
        return response()->json([
            'message' => 'Image deleted successfully',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Find the image by its ID
        $image = imagegallery::find($id);

        // If the image doesn't exist, return a not found response
        if (!$image) {
            return response()->json([
                'message' => 'Image not found',
            ], 404);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image update if provided
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($image->image_url && Storage::exists($image->image_url)) {
                Storage::delete($image->image_url);
            }

            // Get the uploaded file
            $newImage = $request->file('image');

            // Store the new image in the storage directory
            $newImagePath = $newImage->store('uploads', 'public');

            // Get the full URL of the new image
            $newImageUrl = URL::asset('/storage/' . $newImagePath);

            // Update the image URL
            $image->image_url = $newImageUrl;
        }

        // Save the updated image
        $image->save();

        // Return the updated image as a response
        return response()->json([
            'message' => 'Image updated successfully',
            'image' => $image,
        ], 200);
    }
}
