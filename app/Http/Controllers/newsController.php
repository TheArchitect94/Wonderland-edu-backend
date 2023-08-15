<?php

namespace App\Http\Controllers;

use App\Models\news;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class newsController extends Controller
{
    public function index()
    {
        $news = news::all();

        return response()->json([
            'news' => $news,
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
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the uploaded file
        $image = $request->file('image');

        // Check if the image already exists in storage
        $existingImage = news::where('image_url', URL::asset('/storage/' . $image->hashName()))->first();

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

        // Create a new news
        $news = news::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $imageUrl,
        ]);

        // Return the JSON response with the created news
        return response()->json([
            'message' => 'News created successfully',
            'news' => $news,
        ], 201);
    }


    public function destroy($id)
    {
        // Find the news by its ID
        $news = news::find($id);

        // If the news doesn't exist, return a not found response
        if (!$news) {
            return response()->json([
                'message' => 'News not found',
            ], 404);
        }

        // Get the image path
        $imagePath = 'public/uploads/' . basename($news->image_url);

        // Delete the news from the database
        $news->delete();

        // Delete the associated image from storage
        if ($news->image_url && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Return a success message
        return response()->json([
            'message' => 'News deleted successfully',
            'news' => $news,
        ], 200);
    }

    public function edit($id, Request $request)
    {
        // Find the news by its ID
        $news = news::find($id);

        // If the news doesn't exist, return a not found response
        if (!$news) {
            return response()->json([
                'message' => 'News not found',
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
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Update the news data
        $news->title = $request->input('title');
        $news->description = $request->input('description');

        // Handle the image update if provided
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($news->image_url && Storage::exists($news->image_url)) {
                Storage::delete($news->image_url);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('uploads', 'public');
            $news->image_url = $imagePath;
        }

        // Save the updated news
        $news->save();

        // Return the updated news as a response
        return response()->json([
            'message' => 'News updated successfully',
            'news' => $news,
        ], 200);
    }
}
