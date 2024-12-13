<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Subject;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use Illuminate\Support\Facades\Validator;

class ClassListTeacherController extends Controller
{
    /**
     * Return the view for managing class list teachers.
     *
     * @param string $page_content
     * @param array $data
     * @return \Illuminate\View\View
     */
    protected function view_teachers($page_content = 'teachers', $data = [])
    {
        return view("dashboard.classroom.masterclass.manage.class_list_teacher.main_view", array_merge([ 
            'page_content' => $page_content,
        ], $data));
    }

    /**
     * Check if the class list is valid.
     *
     * @param int $masterClass_id
     * @param int $classList_id
     * @param bool $checkBeforeProceed
     * @return true|\Illuminate\Http\JsonResponse
     */
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

    public function index($masterClass_id, $classList_id)
    {
        // Check if class list is valid
        $valid = $this->CheckValidClass($masterClass_id, $classList_id, false);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list with teachers
        $classList = ClassList::with('teachers', 'users')
            ->where('id', $classList_id)
            ->first();

        // Get master class details
        $masterClass = $classList->masterClass;

        // Retrieve all teachers with role_id = 2
        $allTeachers = User::where('role_id', 2)->get();

        // Retrieve assigned teacher IDs
        $assignedTeacherIds = $classList->teachers->pluck('id')->toArray();

        // Retrieve available teachers (not assigned to this class list)
        $availableTeachers = $allTeachers->whereNotIn('id', $assignedTeacherIds);
        // Prepare data for view
        $data = [
            'page_title' => 'Class: ' . $classList->class_name . ' - Teacher Management',
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass->master_class_name,
            'classList_id' => $classList_id,
            
            'classList' => $classList,

            'available_teachers' => $availableTeachers,
        ];

        // Return the view with data
        return $this->view_teachers('teachers', $data);
    }
    
    public function store(Request $request, $masterClass_id, $classList_id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $teacher_id = $request->input('teacher_id');

        // Check if teacher has role_id = 2
        $teacher = User::find($teacher_id);
        if ($teacher->role_id != 2) {
            return response()->json([
                'success' => false,
                'message' => "Selected user is not a teacher.",
            ], 422);
        }

        // Check if class list is valid
        $valid = $this->CheckValidClass($masterClass_id, $classList_id, true);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list
        $classList = ClassList::find($classList_id);

        // Check if the teacher is already assigned to the class list
        if ($classList->teachers()->where('users.id', $teacher_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Teacher is already assigned to this class list.",
            ], 422);
        }

        // Assign the teacher to the class list
        try {
            $classList->teachers()->attach($teacher_id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to assign teacher: " . $e->getMessage(),
            ], 500);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => "Teacher assigned successfully.",
        ], 200);
    }

    public function destroy($masterClass_id, $classList_id, $teacher_id)
    {
        // Validate that teacher_id is numeric
        if (!is_numeric($teacher_id)) {
            return response()->json([
                'success' => false,
                'message' => "Invalid Teacher ID.",
            ], 422);
        }

        // Check if class list is valid
        $valid = $this->CheckValidClass($masterClass_id, $classList_id, true);
        if ($valid !== true) {
            return $valid;
        }

        // Retrieve class list
        $classList = ClassList::find($classList_id);

        // Check if the teacher is assigned to the class list
        if (!$classList->teachers()->where('users.id', $teacher_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Teacher is not assigned to this class list.",
            ], 422);
        }

        // Remove the teacher from the class list
        try {
            $classList->teachers()->detach($teacher_id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to remove teacher: " . $e->getMessage(),
            ], 500);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => "Teacher removed successfully.",
        ], 200);
    }
}