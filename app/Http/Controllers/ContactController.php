<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Mail\Message;


class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::all();

        return response()->json([
            'contact' => $contact,
        ]);
    }
    public function store(Request $request)
    {
        // Define the required fields
        $requiredFields = [
            'fullname',
            'email',
            'phone_number',
            'city',
            'message',
        ];

        // Check if any required fields are missing
        $missingFields = array_diff($requiredFields, array_keys($request->all()));
        if (!empty($missingFields)) {
            return response()->json([
                'message' => 'Required fields are missing: ' . implode(', ', $missingFields),
            ], 400);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'city' => 'required|string',
            'message' => 'required|string',
        ]);

        // If the validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Create a new job
        $contact = Contact::create([
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'city' => $request->input('city'),
            'message' => $request->input('message'),
        ]);

        // Return the JSON response with the created job

        $userEmail = $request->input('email'); // Replace 'email' with the field name of the email in your contact form
        $userName = $request->input('fullname'); // Replace 'fullname' with the field name of the name in your contact form

        Mail::raw('Thank you for contacting us!', function (Message $message) use ($userEmail, $userName) {
            $message->from('aliabbasasif89@gmail.com', 'Wonderland School System');
            $message->to($userEmail);
            $message->subject('Thank you for contacting us!');
            Log::info("Email sent to: " . $userEmail);
        });

        return response()->json([
            'message' => 'Message Sent  successfully',
            'Contact' => $contact,
        ], 201);
    }
    public function destroy($id)
    {
        // Find the job by its ID
        $contact = Contact::find($id);

        // If the job doesn't exist, return a not found response
        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }



        // Delete the job from the database
        $contact->delete();

        // Return a success message
        return response()->json([
            'message' => 'Contact deleted successfully',
        ], 200);
    }
}
