<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Classroom\MasterClassStudents;


class ClassListStudentController extends Controller
{
    protected function view_students($page_content = 'students', $data = [])
    {
        return view("dashboard.classroom.masterclass.manage.class_list_student.main_view", array_merge([ 
            'page_content' => $page_content,
        ], $data));
    }

    public function store(Request $request, $masterClass_id, $classList_id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $student_id = $request->input('student_id');

        $student = User::find($student_id);
        if ($student->role_id != 3) {
            return response()->json([
                'success' => false,
                'message' => "Selected user is not a student.",
            ], 422);
        }

        // Check if student is valid
        $valid = $this->CheckValidStudent($masterClass_id, $classList_id, $student_id, true);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list
        $classList = ClassList::find($classList_id);

        // Check if the student is already assigned to the class list
        if ($classList->students()->where('users.id', $student_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Student is already assigned to this class list.",
            ], 422);
        }

        // Assign the student to the class list
        try {
            $classList->students()->attach($student_id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to assign student: " . $e->getMessage(),
            ], 500);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => "Student assigned successfully.",
        ], 200);
    }

    /**
     * Remove a student from a class list.
     */
    public function destroy($masterClass_id, $classList_id, $student_id)
    {
        // Validate that student_id is numeric
        if (!is_numeric($student_id)) {
            return response()->json([
                'success' => false,
                'message' => "Invalid Student ID.",
            ], 422);
        }

        // Check if student is valid
        $valid = $this->CheckValidStudent($masterClass_id, $classList_id, $student_id, true);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list
        $classList = ClassList::find($classList_id);

        // Check if the student is assigned to the class list
        if (!$classList->students()->where('users.id', $student_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Student is not assigned to this class list.",
            ], 422);
        }

        // Remove the student from the class list
        try {
            $classList->students()->detach($student_id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to remove student: " . $e->getMessage(),
            ], 500);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => "Student removed successfully.",
        ], 200);
    }

    public function index($masterClass_id, $classList_id)
    {
        // Check if class list is valid
        $valid = $this->CheckValidClass($masterClass_id, $classList_id, false);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list with teachers
        $classList = ClassList::with('students', 'users')
            ->where('id', $classList_id)
            ->first();

        // Get master class details
        $masterClass = $classList->masterClass;

        $data = [
            'page_title' => 'Class: ' . $classList->class_name . ' - Student Management',
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass->master_class_name,
            'classList_id' => $classList_id,
            
            'classList' => $classList,
        ];

        // Return the view with data
        return $this->view_students('students', $data);
    }

    protected function CheckValidClass($masterClass_id, $classList_id, $checkBeforeProceed = true)
    {
        // 1. Validate that masterClass_id and classList_id are numeric
        if (!is_numeric($masterClass_id)) {
            abort(404);
        }

        if (!is_numeric($classList_id)) {
            abort(404);
        }

        // 2. Retrieve ClassList with MasterClass using eager loading
        $classList = ClassList::with('masterClass')
            ->where('id', $classList_id)
            ->first();

        // 3. Check if ClassList is found
        if (!$classList) {
            abort(404);
        }

        if ($checkBeforeProceed !== false) {
            // 4. Check if master_class_id from ClassList matches the given masterClass_id
            if ($classList->master_class_id != $masterClass_id) {
                abort(404);
            }

            // 5. Check enrollment_status
            // if ($classList->enrollment_status !== 'Open') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => "Enrollment status is Closed. Operations are not permitted.",
            //     ], 422);
            // }

            // 6. Check if MasterClass is found
            if (!$classList->masterClass) {
                return response()->json([
                    'success' => false,
                    'message' => "Master Class not found.",
                ], 422);
            }

            // 7. Check status of MasterClass
            if ($classList->masterClass->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => "Master Class is not active. Operations are not permitted.",
                ], 422);
            }
        }

        // All checks passed
        return true;
    }

    protected function CheckValidStudent($masterClass_id, $classList_id, $student_id, $checkBeforeProceed = true)
    {
        // 1. Validate that masterClass_id and classList_id are numeric
        if (!is_numeric($masterClass_id)) {
            abort(404);
        }
    
        if (!is_numeric($classList_id)) {
            abort(404);
        }
    
        // 2. Retrieve ClassList with MasterClass using eager loading
        $classList = ClassList::with('masterClass')
            ->where('id', $classList_id)
            ->first();
    
        // 3. Check if ClassList is found
        if (!$classList) {
            return response()->json([
                'success' => false,
                'message' => "Class List not found.",
            ]);
        }
    
        if ($checkBeforeProceed !== false) {
            // 4. Check if master_class_id from ClassList matches the given masterClass_id
            if ($classList->master_class_id != $masterClass_id) {
                abort(404);
            }
    
            // 5. Check MasterClass existence
            if (!$classList->masterClass) {
                return response()->json([
                    'success' => false,
                    'message' => "Master Class not found.",
                ], 422);
            }
    
            // 6. Check status of MasterClass
            if ($classList->masterClass->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => "Master Class is not active. Operations are not permitted.",
                ], 422);
            }
        }
    
        // Setelah semua pengecekan di atas lulus, sekarang cek apakah student_id ini terdaftar pada master_class_students
        $exists = MasterClassStudents::where('master_class_id', $masterClass_id)
            ->where('user_id', $student_id)
            ->exists();
    
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => "Student is not enrolled in this Master Class.",
            ], 422);
        }
    
        return true;
    }
        
}
