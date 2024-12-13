<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Classroom\MasterClass;
use Illuminate\Support\Facades\Validator;
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
        // Temukan academic_year atau gagal
        $academic_year = SchoolAcademicYear::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'academic_year' => [
                'required',
                'string',
                'regex:/^[0-9]{4}-[0-9]{4}$/', // Regex untuk format YYYY-YYYY
                'max:255',
                Rule::unique('academic_years', 'academic_year')->ignore($id),
            ],
            'status' => [
                'required',
                'in:Active,Inactive',
                // Custom Validation Rule untuk memastikan hanya satu tahun akademik aktif
                // function ($attribute, $value, $fail) use ($id) {
                //     if ($value === 'Active') {
                //         $activeCount = SchoolAcademicYear::where('status', 'Active')
                //             ->where('id', '!=', $id)
                //             ->count();
                        
                //         if ($activeCount > 0) {
                //             $fail('Only one academic year can be active at a time.');
                //         }
                //     }
                // },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if($request->input('status') == $academic_year->status) {
            return response()->json([
                'success' => false,
                'message' => 'Status cannot be updated to the same value.',
            ], 422);
        }

        // Mulai transaksi
        DB::beginTransaction();

        try {
            $newStatus = $request->input('status');

            if ($newStatus === 'Active') {
                // Nonaktifkan academic_year lainnya
                $inactiveAcademicYears = SchoolAcademicYear::where('status', 'Active')
                    ->where('id', '!=', $id)
                    ->update(['status' => 'Inactive']);

                // Arsipkan master_classes yang terkait dengan academic_years yang dinonaktifkan dan status Active
                // Pertama, dapatkan semua academic_years yang sekarang 'Inactive' (dari update di atas)
                $inactiveAcademicYearIds = SchoolAcademicYear::where('status', 'Inactive')->pluck('id');

                // Update master_classes terkait academic_years yang dinonaktifkan
                MasterClass::whereIn('academic_year_id', $inactiveAcademicYearIds)
                    ->where('status', 'Active')
                    ->update(['status' => 'Archived']);

                // Aktifkan master_classes terkait academic_year yang baru diaktifkan
                MasterClass::where('academic_year_id', $id)
                    ->where('status', 'Archived') // Hanya master_classes yang sebelumnya di-archive
                    ->update(['status' => 'Active']);
            }

            if ($newStatus === 'Inactive') {
                // Arsipkan semua master_classes yang terkait dengan academic_year ini dan memiliki status Active
                MasterClass::where('academic_year_id', $id)
                    ->where('status', 'Active')
                    ->update(['status' => 'Archived']);
            }

            // Update academic_year
            $academic_year->update([
                'academic_year' => $request->input('academic_year'),
                'status' => $newStatus,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Academic year updated successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error while updating academic year: ' . $e->getMessage(),
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