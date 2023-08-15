<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentResult;
use App\Models\Subject;

class StudentResultController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'class_name' => 'required|string',
            'student_name' => 'required|string',
            'subjects' => 'required|array',
        ]);

        // Extract the subjects array from the request input
        $subjectData = $request->input('subjects');

        // Create the student result
        $studentResult = StudentResult::create([
            'class_name' => $request->input('class_name'),
            'student_name' => $request->input('student_name'),
        ]);

        // Create subjects and associate them with the student result
        $subjectEntries = [];

        foreach ($subjectData as $subject) {
            $subjectEntry = new Subject([
                'subject_name' => $subject['subject_name'],
                'marks' => $subject['marks'],
                'total_marks' => $subject['total_marks'],
            ]);

            // Save the subject and associate it with the student result
            $studentResult->subjects()->save($subjectEntry);
            $subjectEntries[] = $subjectEntry;
        }

        // Build the response in the desired format
        $response = [
            'message' => 'Student result created successfully',
            'class_name' => $studentResult->class_name,
            'student_name' => $studentResult->student_name,
            'subjects' => $subjectEntries,
        ];

        return response()->json($response, 201);
    }

    public function getStudentResults()
    {
        // Fetch all student results with associated subjects
        $studentResults = StudentResult::with('subjects')->get();

        // Organize the student results by class name, student name, and include the ID
        $groupedStudentResults = [];

        foreach ($studentResults as $result) {
            $class = $result->class_name;
            $student = $result->student_name;

            // Initialize class, student, and ID if not exists
            if (!isset($groupedStudentResults[$class])) {
                $groupedStudentResults[$class] = [];
            }
            if (!isset($groupedStudentResults[$class][$student])) {
                $groupedStudentResults[$class][$student] = [
                    'id' => $result->id,
                    'subjects' => [],
                ];
            }

            // Add the subjects to the student
            foreach ($result->subjects as $subject) {
                $groupedStudentResults[$class][$student]['subjects'][] = [
                    'subject_name' => $subject->subject_name,
                    'marks' => $subject->marks,
                    'total_marks' => $subject->total_marks,
                ];
            }
        }

        // Build the response in the desired format
        $responseData = [
            'message' => 'Student results retrieved successfully',
            'student_results' => $groupedStudentResults,
        ];

        return response()->json($responseData, 200);
    }


    public function deleteStudentResult($id)
    {
        // Find the student result by its ID
        $studentResult = StudentResult::find($id);

        if (!$studentResult) {
            return response()->json(['message' => 'Student result not found'], 404);
        }

        // Delete the student result
        $studentResult->delete();

        return response()->json(['message' => 'Student result deleted successfully'], 200);
    }

}
