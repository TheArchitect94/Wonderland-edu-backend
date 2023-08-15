<?php

namespace App\Http\Controllers;

use App\Models\slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class slideController extends Controller
{
    public function index(){
        $slides = slide::all();

        return response()->json([
            'slides' => $slides,
        ]);
    }

    public function store(Request $request)
    {
        // Define the required fields
        $requiredFields = ['title', 'description', 'image'];

        // Check if any required fields are missing
        $missingFields = array_diff($requiredFields, array_keys($request->all()));
        if (!empty($missingFields)) {
            return response()->json([
                'message' => 'Required fields are missing: ' . implode(', ', $missingFields),
            ], 400);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If the validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error,Fields are missing',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the uploaded file
        $image = $request->file('image');

        // Check if the image already exists in storage
        $existingImage = slide::where('image_url', URL::asset('/storage/' . $image->hashName()))->first();

        if ($existingImage) {
            // If the image already exists, return a response with a message
            return response()->json([
                'message' => 'The image has already been uploaded.',
            ], 422);
        }

        // Store the image in the storage directory
        $imagePath = $image->store('uploads', 'public');

        // Get the full URL of the stored image
        $imageUrl = URL::asset('/storage/' . $imagePath);

        // Create a new slide
        $slide = slide::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $imageUrl,
        ]);

        // Return the JSON response with the created news
        return response()->json([
            'message' => 'Slide created successfully',
            'Slide' => $slide,
        ], 201);
    }




    public function destroy($id)
    {
        // Find the slide by its ID
        $slide = Slide::find($id);

        // If the slide doesn't exist, return a not found response
        if (!$slide) {
            return response()->json([
                'message' => 'Slide not found',
            ], 404);
        }

        // Get the image path
        $imagePath = 'public/uploads/' . basename($slide->image_url);

        // Delete the slide from the database
        $slide->delete();

        // Delete the associated image from storage
        if ($slide->image_url && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Return a success message
        return response()->json([
            'message' => 'Slide deleted successfully',
            'slide' => $slide,
        ], 200);
    }


    public function edit($id, Request $request)
    {
        // Find the slide by its ID
        $slide = Slide::find($id);

        // If the slide doesn't exist, return a not found response
        if (!$slide) {
            return response()->json([
                'message' => 'Slide not found',
            ], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If the validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error,Fields are missing',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Update the slide data
        $slide->title = $request->input('title');
        $slide->description = $request->input('description');

        // Handle the image update if provided
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($slide->image_url && Storage::exists($slide->image_url)) {
                Storage::delete($slide->image_url);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('uploads', 'public');
            $slide->image_url = $imagePath;
        }

        // Save the updated slide
        $slide->save();

        // Return the updated slide as a response
        return response()->json([
            'message' => 'Slide updated successfully',
            'slide' => $slide,
        ], 200);
    }




}
