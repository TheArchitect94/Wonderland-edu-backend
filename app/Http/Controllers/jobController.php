<?php

namespace App\Http\Controllers;

use App\Models\jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class jobController extends Controller
{
    public function index()
    {
        $jobs = jobs::all();

        return response()->json([
            'jobs' => $jobs,
        ]);
    }

    public function store(Request $request)
    {
        // Define the required fields
        $requiredFields = ['title', 'description', 'image', 'joboffer'];

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
            'joboffer' => 'required|string',
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
        $existingImage = jobs::where('image_url', URL::asset('/storage/' . $image->hashName()))->first();

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

        // Create a new job
        $job = jobs::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_url' => $imageUrl,
            'joboffer' => $request->input('joboffer'),
        ]);

        // Return the JSON response with the created job
        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job,
        ], 201);
    }


    public function destroy($id)
    {
        // Find the job by its ID
        $job = jobs::find($id);

        // If the job doesn't exist, return a not found response
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }

        // Get the image path
        $imagePath = 'public/uploads/' . basename($job->image_url);

        // Delete the job from the database
        $job->delete();

        // Delete the associated image from storage
        if ($job->image_url && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Return a success message
        return response()->json([
            'message' => 'Job deleted successfully',
        ], 200);
    }

    public function edit($id, Request $request)
    {
        // Find the job by its ID
        $job = jobs::find($id);

        // If the job doesn't exist, return a not found response
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'joboffer' => 'required|boolean',
        ]);

        // If the validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Update the job data
        $job->title = $request->input('title');
        $job->description = $request->input('description');
        $job->joboffer = $request->input('joboffer');

        // Handle the image update if provided
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($job->image_url && Storage::exists($job->image_url)) {
                Storage::delete($job->image_url);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('uploads', 'public');
            $job->image_url = $imagePath;
        }

        // Save the updated job
        $job->save();

        // Return the updated job as a response
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job,
        ], 200);
    }
}
