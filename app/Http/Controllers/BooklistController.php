<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booklist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class BooklistController extends Controller
{
    public function index(Request $request)
    {
        $books = Booklist::all();

        $booksGroupedByClass = $books->groupBy('class_name');

        $responseData = [];

        foreach ($booksGroupedByClass as $class => $classBooks) {
            $classData = [
                'classname' => $class,
                'books' => $classBooks->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'class_name' => $book->class_name,
                        'book_title' => $book->book_title,
                        'book_description' => $book->book_description,
                        'book_price' => $book->book_price,
                        'image_url' => $book->image_url,
                        'created_at' => $book->created_at,
                        'updated_at' => $book->updated_at,
                    ];
                }),
            ];

            $responseData[] = $classData;
        }

        return response()->json($responseData);
    }


    public function store(Request $request)
    {
        // Validation rules including the dynamic class field
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string',
            'book_title' => 'required|string',
            'book_description' => 'required|string',
            'book_price' => 'required|string',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Handle file upload if provided
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('uploads', 'public');
            $imageUrl = URL::asset('/storage/' . $imagePath);
        } else {
            $imageUrl = null;
        }

        // Create a new book
        $book = Booklist::create([
            'class_name' => $request->input('class_name'),
            'book_title' => $request->input('book_title'),
            'book_description' => $request->input('book_description'),
            'book_price' => $request->input('book_price'),
            'image_url' => $imageUrl,
        ]);

        // Retrieve all books grouped by class name
        $booksGroupedByClass = Booklist::all()->groupBy('class_name');

        // Build the response data
        $responseData = [
            'message' => 'Book created successfully',
        ];

        foreach ($booksGroupedByClass as $class => $books) {
            $classData = [];

            foreach ($books as $bookItem) {
                $classData[] = [
                    'book_title' => $bookItem->book_title,
                    'book_description' => $bookItem->book_description,
                    'book_price' => $bookItem->book_price,
                    'image_url' => $bookItem->image_url,
                    'updated_at' => $bookItem->updated_at,
                    'created_at' => $bookItem->created_at,
                    'id' => $bookItem->id,
                ];
            }

            $responseData[$class] = $classData;
        }

        return response()->json($responseData, 201);
    }

    public function destroy($id)
    {
        // Find the book by its ID
        $book = Booklist::find($id);

        // If the book doesn't exist, return a not found response
        if (!$book) {
            return response()->json([
                'message' => 'Book not found',
            ], 404);
        }

        // Get the image path
        $imagePath = 'public/uploads/' . basename($book->image_url);

        // Delete the book from the database
        $book->delete();

        // Delete the associated image from storage
        if ($book->image_url && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Return a success message
        return response()->json([
            'message' => 'Book deleted successfully',
            'book' => $book,
        ], 200);
    }


}
