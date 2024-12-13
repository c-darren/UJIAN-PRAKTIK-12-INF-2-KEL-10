<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;
use Illuminate\Support\Facades\Validator;
use App\Models\Classroom\MasterClassStudents;

use function PHPUnit\Framework\isEmpty;

class MasterClassStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function CheckValidClass($masterClass_id, $enrollment_id = null, $userId = null)
    {
        if(is_null($enrollment_id) && is_null($userId)) {
            $classesValid = DB::table('master_class_students')
                ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
                // ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
                ->select(
                    'master_classes_students.user_id as master_classes_students_user_id',
                )
                ->where('master_classes.id', $masterClass_id)
                ->where('master_classes.status', 'Active') // Kelas harus aktif
                // ->where('academic_years.status', 'Active') // Tahun ajaran harus aktif
                ->count();
            if ($classesValid != 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Master Class, Academic Year, or Student. Ensure they are valid.",
                ], 422);
            }
        }else{
            $classesValid = DB::table('master_class_students')
                ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
                // ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
                ->select(
                    'master_classes.id as master_class_id',
                    // 'academic_years.status as academic_year_status',
                    'master_classes.status as master_class_status',
                    'master_class_students.status as student_status'
                )
                ->where('master_classes.id', $masterClass_id)
                ->where('master_classes.status', 'Active') // Kelas harus aktif
                // ->where('academic_years.status', 'Active') // Tahun ajaran harus aktif
                ->where('master_class_students.' . ($userId !== null ? 'user_id' : 'id'), $userId !== null ? $userId : $enrollment_id)
                ->count();
            if ($classesValid != 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Master Class, Academic Year, or Student. Ensure they are valid.",
                ], 422);
            }
        }
        return true;
    }

    public function view_students($masterClass_id)
    {
        $masterClass = MasterClass::find($masterClass_id);

        if (!$masterClass || empty($masterClass->master_class_name)) {
            abort(404);
        }
        
        $masterClass_name = $masterClass->master_class_name;
        return $this->view_master_students('all_master_students', [
            'page_title' => $masterClass_name . ' - Master Class Students',
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass_name
        ]);
    }

    public function create_students($masterClass_id)
    {
        $masterClass_name = MasterClass::find($masterClass_id)->master_class_name;

        $this->CheckValidClass($masterClass_id);
        $notInRecords = DB::table('master_class_students')
            ->join('master_classes', 'master_class_students.master_class_id', '=', 'master_classes.id')
            // ->join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
            ->select(
                'master_class_students.user_id as master_class_student_user_id',
            )
            ->where('master_classes.id', $masterClass_id)
            ->where('master_classes.status', 'Active') // Kelas harus aktif
            // ->where('academic_years.status', 'Active') // Tahun ajaran harus aktif
            ->get();
        
        if($notInRecords->isEmpty()) {
            $notInRecords = DB::table('master_classes')
            ->select(
                'master_classes.status as master_class_status',
            )
            ->where('master_classes.id', $masterClass_id)
            ->where('master_classes.status', 'Active') // Kelas harus aktif
            ->get();
            if($notInRecords->isEmpty()) {
                $records = "Invalid";
            }else{
                $records = User::where('role_id', '=', 3)
                    ->select('id', 'name')
                    ->get();
            }
        }else{
            $records = User::where('role_id', '=', 3)
                ->whereNotIn('id', $notInRecords->pluck('master_class_student_user_id'))
                ->select('id', 'name')
                ->get();
        }
            
        return $this->view_master_students('create_master_students', [
            'page_title' => 'New Master Class Students',
            'records' => $records,
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass_name
        ]);
    }

    public function store_student(Request $request, $masterClass_id)
    {
        $request->validate([
            'student_id' => 'required|string',
        ]);
    
        $userId = $request->input('student_id');
    
        if (!is_numeric($masterClass_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Master Class ID.',
            ]);
        }
    
        if (!is_numeric($userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Student ID.',
            ]);
        }
    
        // Cek validitas master class dan user
        $this->CheckValidClass($masterClass_id, '', $userId);
    
        // Cek role user: harus student (role_id=3)
        $checkRole = User::where('id', $userId)
            ->select('role_id')
            ->first();
    
        if (!$checkRole || $checkRole->role_id !== 3) {
            return response()->json(['success' => false, 'message' => 'Invalid! Role must be student.'], 422);
        }
    
        // Cek apakah student sudah terdaftar di master_class_students
        $exists = MasterClassStudents::where('master_class_id', $masterClass_id)
            ->where('user_id', $userId)
            ->exists();
    
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Student is already enrolled in this Master Class.'
            ], 422);
        }
    
        // Mulai transaksi
        DB::beginTransaction();
    
        try {
            MasterClassStudents::create([
                'master_class_id' => $masterClass_id,
                'user_id' => $userId,
                'status' => 'Enrolled',
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Student added successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student. '
            ]);
        }
    }
    
    public function delete_student(Request $request, $masterClass_id, $enrollment_id) 
    {
        if (!is_numeric($masterClass_id) || !is_numeric($enrollment_id)) {
            return response()->json(['message' => 'Invalid Master Class ID or Student ID.'], 422);
        }
        
        $valid = $this->CheckValidClass($masterClass_id, $enrollment_id);
    
        if ($valid !== true) {
            return $valid;
        }
    
        DB::beginTransaction();
    
        try {
            $enrollment = MasterClassStudents::where('id', $enrollment_id)->firstOrFail();
            
            // Simpan user_id untuk nanti digunakan menghapus dari class_students
            $user_id = $enrollment->user_id;
            
            // Hapus dari master_class_students
            $enrollment->delete();
    
            // Cari semua class_list yang terhubung dengan masterClass_id ini
            $classListIds = ClassList::where('master_class_id', $masterClass_id)->pluck('id');
    
            // Hapus dari class_students untuk user tersebut pada semua class_list terkait master_class_id
            DB::table('class_students')
                ->whereIn('class_id', $classListIds)
                ->where('user_id', $user_id)
                ->delete();
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student: ' . $e->getMessage()
            ], 500);
        }
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

    protected function view_master_students($page_content = 'all_master_students', $data = [])
    {
        return view("dashboard.classroom.masterclass.manage.master_student.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}