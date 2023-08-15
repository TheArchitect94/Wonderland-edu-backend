<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimetableEntry;
use Illuminate\Support\Facades\Log;

class TimetableController extends Controller
{
    public function getTimetable(Request $request)
    {
        $timetableEntries = TimetableEntry::all();

        // Group the timetable entries by class name and day
        $groupedTimetable = [];
        foreach ($timetableEntries as $entry) {
            $classname = $entry->classname;
            $day = $entry->day;

            // Initialize the class and day if not exists
            if (!isset($groupedTimetable[$classname])) {
                $groupedTimetable[$classname] = [];
            }
            if (!isset($groupedTimetable[$classname][$day])) {
                $groupedTimetable[$classname][$day] = [];
            }

            // Add the timetable entry
            $groupedTimetable[$classname][$day][] = [
                'id' => $entry->id,
                'start_time' => $entry->start_time,
                'end_time' => $entry->end_time,
                'subject' => $entry->subject,
            ];
        }

        // Create the response data
        $responseData = [
            'message' => 'Timetable entries retrieved successfully',
            'timetable' => $groupedTimetable,
        ];

        // Return the response as JSON
        return response()->json($responseData, 200);
    }



    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'classname' => 'required|string',
                'day' => 'required|string',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
                'subject' => 'required|string',
            ]);

            // If the validation passes, create the timetable entry
            $timetableEntry = TimetableEntry::create($data);

            $responseData = [
                'message' => 'Timetable entry created successfully',
                'entry' => $timetableEntry,
            ];

            return response()->json($responseData, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If there are validation errors, return a detailed error response
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error($e);

            // Return a generic error response
            return response()->json([
                'message' => 'An error occurred while creating the timetable entry.',
            ], 500);
        }
    }




    public function destroyTimetableEntry($id)
{
    // Find the timetable entry by its ID
    $timetableEntry = TimetableEntry::find($id);

    // If the timetable entry doesn't exist, return a not found response
    if (!$timetableEntry) {
        return response()->json([
            'message' => 'Timetable entry not found',
        ], 404);
    }

    // Delete the timetable entry from the database
    $timetableEntry->delete();

    // Return a success message
    return response()->json([
        'message' => 'Timetable entry deleted successfully',
        'entry' => $timetableEntry,
    ], 200);
}

}

