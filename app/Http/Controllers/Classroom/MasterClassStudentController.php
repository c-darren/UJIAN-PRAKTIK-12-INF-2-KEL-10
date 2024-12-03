<?php

namespace App\Http\Controllers\Classroom;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;
use App\Models\Classroom\MasterClassStudents;

class MasterClassStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    
    public function joinClass(Request $request)
{
    $request->validate([
        'join_class_code' => 'required|string',
    ]);

    $classCode = $request->input('join_class_code');
    $masterClass = MasterClass::where('master_class_code', $classCode)
        ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
        ->select('master_classes.id', 'master_classes.status', 'academic_years.id as academic_year_id')
        ->first();

    if (!$masterClass) {
        return response()->json([
            'success' => false,
            'message' => "Class code: " . htmlspecialchars($classCode) . " not found",
        ], 500);
    }

    if ($masterClass->status == 'Active') {
        $existingEnrollment = MasterClassStudents::where('master_class_id', $masterClass->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingEnrollment) {
            MasterClassStudents::create([
                'master_class_id' => $masterClass->id,
                'user_id' => Auth::id(),
                'status' => 'Enrolled',
            ]);
            return response()->json([
                'success' => true,
                'message' => "Successfully enrolled in the class",
                'redirect_url' => route('master-class.enrolled-class'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "You are already enrolled in this class",
            ], 500);
        }
    }
    return response()->json([
        'success' => false,
        'message' => "This class is archived",
    ], 500);
    }

    public function exitClass(Request $request)
    {
        $request->validate([
            'm_class_id' => 'required|string',
        ]);

        $classId = $request->input('m_class_id');
        $masterClass = MasterClass::find($classId);

        if (!$masterClass) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $academicYearStatus = DB::table('academic_years')
            ->where('id', $masterClass->academic_year_id)
            ->value('status');
        
        if ($masterClass->status == 'Active' && $academicYearStatus == 'Active') {
            $existingEnrollment = MasterClassStudents::where('master_class_id', $masterClass->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingEnrollment) {
                $existingEnrollment->status = 'Exited';
                $existingEnrollment->save();
                return response()->json([
                    'success' => true,
                    'message' => "Successfully exited the class",
                    'redirect_url' => route('master-class.exited-class'),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You are not enrolled in this class",
                ], 500);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Class is not active or academic year is inactive',
        ], 400);
    }

    public function showEnrolled()
    {
        $userId = Auth::id();

        $classes = DB::table('master_class_students')
            ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
            ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
            ->select(
                'master_classes.id as master_class_id',
                'master_classes.master_class_name as master_class_name',
                'academic_years.academic_year',
                'master_classes.status as master_class_status',
            )
            ->where('master_classes.status', 'Active')
            ->where('academic_years.status', 'Active')
            ->where('master_class_students.user_id', $userId)
            ->where('master_class_students.status', 'Enrolled')
            ->get();

        return $this->view('view_masterclasses', ['page_title' => 'Active Master Classes', 'records' => $classes]);
    }

    public function showArchivedMClass()
    {
        $userId = Auth::id();

        $classes = DB::table('master_class_students')
            ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
            ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
            ->select(
                'master_classes.id as master_class_id',
                'master_classes.master_class_name as master_class_name',
                'academic_years.academic_year',
                'master_classes.status as master_class_status',
            )
            ->where(function($query) {
                $query->where('master_classes.status', 'Archived')
                    ->orWhere('academic_years.status', 'Inactive');
            })
            ->where('master_class_students.user_id', $userId)
            ->get();

        return $this->view('view_masterclasses', ['page_title' => 'Archived Master Classes', 'records' => $classes]);
    }

    public function showExitedMClass()
    {
        $userId = Auth::id();

        $classes = DB::table('master_class_students')
            ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
            ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
            ->select(
                'master_classes.id as master_class_id',
                'master_classes.master_class_name as master_class_name',
                'academic_years.academic_year',
                'academic_years.status as academic_year_status',
                'master_classes.status as master_class_status',
            )
            ->where('master_class_students.user_id', $userId)
            ->where('master_class_students.status', 'Exited')
            ->get();

        return $this->view('view_masterclasses', ['page_title' => 'Exited Master Classes', 'records' => $classes]);
    }

    public function rejoinClass(Request $request)
    {
        $request->validate([
            'm_class_id' => 'required|string',
        ]);

        $classId = $request->input('m_class_id');
        $masterClass = MasterClass::find($classId);

        if (!$masterClass) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $academicYearStatus = DB::table('academic_years')
            ->where('id', $masterClass->academic_year_id)
            ->value('status');

        if ($masterClass->status == 'Active' && $academicYearStatus == 'Active') {
            $existingEnrollment = MasterClassStudents::where('master_class_id', $masterClass->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingEnrollment && $existingEnrollment->status == 'Exited') {
                $existingEnrollment->status = 'Enrolled';
                $existingEnrollment->save();
                return response()->json([
                    'success' => true,
                    'message' => "Successfully rejoined the class",
                    'redirect_url' => route('master-class.enrolled-class'),
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => "You are already enrolled in this class",
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Class is not active or academic year is inactive',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterClassStudents $masterClassStudents)
    {
        // // Uncomment jika ingin menghapus data enrollment (dinonaktifkan)
        // $masterClassStudents->delete();

        // return back()->with('success', 'Berhasil keluar dari kelas.');
    }

    protected function view($page_content = 'main_view', $data = [])
    {
        return view("dashboard.classroom.masterclass_student.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}
