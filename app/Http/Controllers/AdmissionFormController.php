<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdmissionForm;
use Illuminate\Support\Facades\Validator;

class AdmissionFormController extends Controller
{
    /**
     * Get all admission forms.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $admissionForms = AdmissionForm::all();

        return response()->json([
            'admission_forms' => $admissionForms,
        ]);
    }

    /**
     * Create a new admission form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define the required fields
        $requiredFields = [
            'student_name',
            'previous_class',
            'previous_school',
            'apply_class',
            'religion',
            'gender',
            'place_of_birth',
            'date_of_birth',
            'father_name',
            'father_cnic_no',
            'father_cell_no',
            'father_whatsapp_no',
            'father_email',
            'father_education',
            'father_occupation',
            'mother_name',
            'mother_cnic_no',
            'mother_cell_no',
            'mother_education',
            'mother_whatsapp_no',
            'mother_email',
            'mother_occupation',
            'guardian_name',
            'guardian_cnic_no',
            'guardian_cell_no',
            'guardian_whatsapp_no',
            'guardian_email',
            'guardian_education',
            'guardian_occupation',
            'address',
            'postal_code',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string',
            'previous_class' => 'required|string',
            'previous_school' => 'required|string',
            'apply_class' => 'required|string',
            'religion' => 'required|string',
            'gender' => 'required|string',
            'place_of_birth' => 'required|string',
            'date_of_birth' => 'required|string',
            'father_name' => 'required|string',
            'father_cnic_no' => 'required|string',
            'father_cell_no' => 'required|string',
            'father_whatsapp_no' => 'required|string',
            'father_email' => 'required|string',
            'father_education' => 'required|string',
            'father_occupation' => 'required|string',
            'mother_name' => 'required|string',
            'mother_cnic_no' => 'required|string',
            'mother_cell_no' => 'required|string',
            'mother_education' => 'required|string',
            'mother_whatsapp_no' => 'required|string',
            'mother_email' => 'required|string',
            'mother_occupation' => 'required|string',
            'address' => 'required|string',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Create the new admission form
        $admissionForm = AdmissionForm::create($request->all());

        return response()->json([
            'message' => 'Admission form created successfully',
            'admission_form' => $admissionForm,
        ], 201);
    }

    /**
     * Get a specific admission form by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $admissionForm = AdmissionForm::find($id);

        if (!$admissionForm) {
            return response()->json([
                'message' => 'Admission form not found',
            ], 404);
        }

        return response()->json([
            'admission_form' => $admissionForm,
        ]);
    }

    /**
     * Update an admission form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the admission form by ID
        $admissionForm = AdmissionForm::find($id);

        // Check if the admission form exists
        if (!$admissionForm) {
            return response()->json([
                'message' => 'Admission form not found',
            ], 404);
        }

        // Define the required fields
        $requiredFields = [
            'student_name',
            'previous_class',
            'previous_school',
            'apply_class',
            'religion',
            'gender',
            'place_of_birth',
            'date_of_birth',
            'father_name',
            'father_cnic_no',
            'father_cell_no',
            'father_whatsapp_no',
            'father_email',
            'father_education',
            'father_occupation',
            'mother_name',
            'mother_cnic_no',
            'mother_cell_no',
            'mother_education',
            'mother_whatsapp_no',
            'mother_email',
            'mother_occupation',
            'guardian_name',
            'guardian_cnic_no',
            'guardian_cell_no',
            'guardian_whatsapp_no',
            'guardian_email',
            'guardian_education',
            'guardian_occupation',
            'address',
            'postal_code',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string',
            'previous_class' => 'required|string',
            'previous_school' => 'required|string',
            'apply_class' => 'required|string',
            'religion' => 'required|string',
            'gender' => 'required|string',
            'place_of_birth' => 'required|string',
            'date_of_birth' => 'required|string',
            'father_name' => 'required|string',
            'father_cnic_no' => 'required|string',
            'father_cell_no' => 'required|string',
            'father_whatsapp_no' => 'required|string',
            'father_email' => 'required|string',
            'father_education' => 'required|string',
            'father_occupation' => 'required|string',
            'mother_name' => 'required|string',
            'mother_cnic_no' => 'required|string',
            'mother_cell_no' => 'required|string',
            'mother_education' => 'required|string',
            'mother_whatsapp_no' => 'required|string',
            'mother_email' => 'required|string',
            'mother_occupation' => 'required|string',
            'address' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Update the admission form
        $admissionForm->update($request->all());

        return response()->json([
            'message' => 'Admission form updated successfully',
            'admission_form' => $admissionForm,
        ]);
    }

    /**
     * Delete an admission form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the admission form by ID
        $admissionForm = AdmissionForm::find($id);

        if (!$admissionForm) {
            return response()->json([
                'message' => 'Admission form not found',
            ], 404);
        }

        // Delete the admission form
        $admissionForm->delete();

        return response()->json([
            'message' => 'Admission form deleted successfully',
        ]);
    }
}
