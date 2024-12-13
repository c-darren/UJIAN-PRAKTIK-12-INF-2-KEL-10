<?php

namespace App\Http\Controllers\Classroom;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\School\AcademicYear;
use App\Http\Controllers\Controller;
use App\Models\Classroom\MasterClass;

class MasterClassController extends Controller
{
    // Show the list view (single page CRUD)
    public function show()
    {
        $academicYears = AcademicYear::where('status', 'Active')->select('id', 'academic_year')->get();
        
        return $this->view('view_masterclass', compact('academicYears'));
    }

    // Store a new Master Class
    public function store(Request $request)
    {
        $request->validate([
            'master_class_name' => [
                'required',
                'string',
                'max:255',
            ],
            'master_class_code' => [
                'required',
                'string',
                'max:8',
                'unique:master_classes,master_class_code', // Ensure code is unique
            ],
            'academic_year_id' => [
                'required',
                'exists:academic_years,id', // Validate academic year exists
                Rule::exists('academic_years', 'id')->where(function ($query) {
                    $query->where('status', 'Active');
                }),
            ],
            'status' => [
                'required',
                'in:Archived,Active', // Ensure status is one of the valid options
            ],
        ]);

        $academicYears = AcademicYear::where('status', 'Active')->select('id', 'academic_year')->get();
        if($academicYears->isEmpty()) {
            return response()->json([
                'message' => 'No active academic year found.',
            ]);
        }

        try {
            $masterClass = new MasterClass();
            $masterClass->master_class_name = $request->input('master_class_name');
            $masterClass->master_class_code = $request->input('master_class_code');
            $masterClass->academic_year_id = $request->input('academic_year_id');
            $masterClass->status = $request->input('status');
            $masterClass->save();

            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving master class: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Update an existing Master Class
    public function update(Request $request, $id)
    {
        $masterClass = MasterClass::findOrFail($id);

        $academicYear = AcademicYear::findOrFail($masterClass->academic_year_id);
        
        if ($academicYear->status === 'Inactive') {
            return response()->json([
                'success' => false,
                'message' => 'The academic year is inactive, status cannot be updated.',
            ], 422);
        }

        $request->validate([
            'master_class_name' => [
                'required',
                'string',
                'max:255',
            ],
            'master_class_code' => [
                'required',
                'string',
                'max:8',
                Rule::unique('master_classes', 'master_class_code')->ignore($id),
            ],
            'academic_year_id' => [
                'required',
                'exists:academic_years,id', // Validate academic year exists
                Rule::exists('academic_years', 'id')->where(function ($query) {
                    $query->where('status', 'Active');
                }),
            ],
            'status' => [
                'required',
                'in:Archived,Active', // Ensure status is one of the valid options
            ],
        ]);

        try {
            $masterClass->update([
                'master_class_name' => $request->input('master_class_name'),
                'master_class_code' => $request->input('master_class_code'),
                'academic_year_id' => $request->input('academic_year_id'),
                'status' => $request->input('status'),
            ]);
            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while updating master class: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Delete a Master Class
    public function destroy($id)
    {
        try {
            $masterClass = MasterClass::findOrFail($id);
            $masterClass->delete();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while deleting master class: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Return the view with the necessary data
    protected function view($page_content = 'main_view', $data = [])
    {
        return view("dashboard.classroom.masterclass.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}