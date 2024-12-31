<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Models\Classroom\Subject;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;
use Illuminate\Support\Facades\Validator;
use App\Models\Classroom\MasterClassStudents;
use App\ClassList\Student\Traits\AuthorizeAccess;

class StudentClassListController extends Controller
{
    use AuthorizeAccess;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $masterClass_id, $classList_id)
    {
        $student_id = Auth::user()->id;

        // Cek role
        $student = User::find($student_id);
        if ($student->role_id != 3) {
            return response()->json([
                'success' => false,
                'message' => "Anda bukan peserta didik.",
            ], 422);
        }

        // checkEnrollmentStudent sudah melakukan pengecekan enrollment
        $classList = $this->checkEnrollmentStudent($masterClass_id, $classList_id, true);
        if (!($classList instanceof ClassList)) {
            return $classList;
        }

        // Langsung attach karena sudah divalidasi tidak ada enrollment
        try {
            $classList->students()->attach($student_id);
            return response()->json([
                'success' => true,
                'message' => "Berhasil bergabung ke kelas.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Gagal bergabung ke kelas: " . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showClassList($masterClass_id)
    {
        // Validasi Master Class
        $check = $this->CheckValidClass($masterClass_id);
        if ($check !== true) {
            return $check;
        }

        // Gunakan relasi dari ClassList untuk mendapatkan data
        $classList = ClassList::with(['subject:id,subject_name'])
            ->withCount(['students' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->where('master_class_id', $masterClass_id)
            ->select('id', 'class_name', 'subject_id', 'enrollment_status')
            ->paginate(10);

        $masterClass = MasterClass::select('id', 'master_class_name')
            ->findOrFail($masterClass_id);

        return $this->view('available_class_lists', [
            'page_title' => $masterClass->master_class_name . ' - Class Lists',
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass->master_class_name,
            'records' => $classList,
            'subjects' => Subject::select('id', 'subject_name')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }

    // public function visitClass($masterClass_id, $classList_id)
    // {
    //     $valid = $this->authorizeStudent($masterClass_id, $classList_id, true);
    //     if ($valid !== true) {
    //         return $valid;
    //     }

    //     // Gunakan lazy loading untuk subject karena hanya 1 data
    //     $classList = ClassList::select('id', 'class_name', 'subject_id', 'master_class_id')
    //         ->findOrFail($classList_id);

    //     return $this->view('visit_class', [
    //         'page_title' => $classList->class_name . ' - Detail Kelas',
    //         'masterClass_id' => $masterClass_id,
    //         'masterClass_name' => $classList->masterClass->master_class_name,
    //         'class_id' => $classList_id,
    //         'classList' => $classList,
    //         'subject' => $classList->subject, // Lazy load subject
    //     ]);
    // }

    protected function view($page_content = 'all_master_students', $data = [])
    {
        return view("dashboard.classroom.student.class_list.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}
