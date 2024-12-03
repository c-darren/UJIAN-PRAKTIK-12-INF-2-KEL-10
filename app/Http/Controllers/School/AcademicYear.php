<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\School\AcademicYear as SchoolAcademicYear;

class AcademicYear extends Controller
{
    public function show()
    {
        return $this->view('view_academic_years');
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => [
                'required',
                'string',
                'regex:/^[0-9]{4}-[0-9]{4}$/', // Regex for YYYY-YYYY format
                'max:255',
                'unique:academic_years,academic_year',
            ],
            'status' => [
                'required',
                'in:Active,Inactive',
                // Custom Validation Rule untuk memastikan hanya satu tahun akademik aktif
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'Active') {
                        $activeCount = SchoolAcademicYear::where('status', 'Active')->count();
                        
                        if ($activeCount > 0) {
                            $fail('Only one academic year can be active at a time.');
                        }
                    }
                },
            ],
        ]);
    
        try {
            $academic_years = new SchoolAcademicYear();
            $academic_years->academic_year = $request->input('academic_year');
            $academic_years->status = $request->input('status');
            $academic_years->updated_at = null;
            $academic_years->save();
        
            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving academic years: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $academic_years = SchoolAcademicYear::findOrFail($id);
    
        $request->validate([
            'academic_year' => [
                'required',
                'string',
                'regex:/^[0-9]{4}-[0-9]{4}$/', // Regex for YYYY-YYYY format
                'max:255',
                Rule::unique('academic_years', 'academic_year')->ignore($id),
            ],
            'status' => [
                'required',
                'in:Active,Inactive',
                // Custom Validation Rule untuk memastikan hanya satu tahun akademik aktif
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'Active') {
                        $activeCount = SchoolAcademicYear::where('status', 'Active')->count();
                        
                        if ($activeCount > 0) {
                            $fail('Only one academic year can be active at a time.');
                        }
                    }
                },
            ],
        ]);
    
        try {
            $academic_years->update([
                'academic_year' => $request->input('academic_year'),
                'status' => $request->input('status'),
            ]);
            return response()->json([
                'success' => true,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while update academic years: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $academic_years = SchoolAcademicYear::findOrFail($id);
            $academic_years->delete();
    
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while deleting role: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function view($page_content = 'view_academic_years', $data = [])
    {
        return view("dashboard.school.academic_year.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}