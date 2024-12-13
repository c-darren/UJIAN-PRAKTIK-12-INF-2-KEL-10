<?php

namespace App\Http\Controllers\Classroom;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Classroom\Subject;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use App\Models\Classroom\MasterClass;
use Illuminate\Support\Facades\Validator;

class ClassListController extends Controller
{
    public function index($masterClass_id)
    {
        // Validasi Master Class
        $check = $this->CheckValidClass($masterClass_id);
        if ($check !== true) {
            return $check;
        }

        $masterClass_name = MasterClass::find($masterClass_id)->master_class_name;

        $classLists = ClassList::with(['subject:id,subject_name'])
        ->select('id', 'class_name', 'subject_id', 'enrollment_status')
        ->where('master_class_id', $masterClass_id)
        ->paginate(10);

        $subjects = Subject::select('id', 'subject_name')->get();

        // Render view dengan data yang diperlukan
        return $this->view_class_lists('available_class_lists', [
            'page_title' => $masterClass_name . ' - Class Lists',
            'masterClass_id' => $masterClass_id,
            'masterClass_name' => $masterClass_name,
            'records' => $classLists,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request, $masterClass_id)
    {
        
        // Validasi Input
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'enrollment_status' => 'required|in:Open,Closed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        // Validasi Master Class
        $check = $this->CheckValidClass($masterClass_id, true);
        if ($check !== true) {
            return $check;
        }

        try {
            // Simpan Class List
            ClassList::create([
                'master_class_id' => $masterClass_id,
                'class_name' => $request->input('class_name'),
                'subject_id' => $request->input('subject_id'),
                'enrollment_status' => $request->input('enrollment_status'),
            ]);

            // Muat relasi Subject
            // $classList->load('subject');

            return response()->json([
                'success' => true,
                'message' => 'Class list created successfully.',
            ], 201);

        } catch (\Exception $e) {
            // Tangani Error
            return response()->json([
                'success' => false,
                'message' => 'Error while creating class list: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $masterClass_id, $id)
    {
        // dump($request->all());
        // Validasi Master Class
        $check = $this->CheckValidClass($masterClass_id);
        if ($check !== true) {
            return $check;
        }

        // Temukan Class List
        $classList = ClassList::findOrFail($id);

        // Validasi Input
        // $validator = Validator::make($request->all(), [
        //     'class_name' => 'required|string|max:255',
        //     'subject_id' => 'required|exists:subjects,id',
        //     'enrollment_status' => 'required|in:Open,Closed',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $validator->errors()->first(),
        //     ], 422);
        // }

        try {
            // Update Class List
            $classList->update([
                'class_name' => $request->input('class_name'),
                'subject_id' => $request->input('subject_id'),
                'enrollment_status' => $request->input('enrollment_status'),
            ]);

            // Muat relasi Subject
            $classList->load('subject');

            return response()->json([
                'success' => true,
                'message' => 'Class list updated successfully.',
                'records' => $classList,
            ], 200);

        } catch (\Exception $e) {
            // Tangani Error
            return response()->json([
                'success' => false,
                'message' => 'Error while updating class list: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($masterClass_id, $id)
    {
        // Validasi Master Class
        $check = $this->CheckValidClass($masterClass_id);
        if ($check !== true) {
            return $check;
        }

        // Temukan Class List
        $classList = ClassList::findOrFail($id);

        try {
            // Hapus Class List
            $classList->delete();

            return response()->json([
                'success' => true,
                'message' => 'Class list deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            // Tangani Error
            return response()->json([
                'success' => false,
                'message' => 'Error while deleting class list: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function view_class_lists($page_content = 'available_class_lists', $data = [])
    {
        return view("dashboard.classroom.masterclass.manage.class_list.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }

    protected function CheckValidClass($masterClass_id, $checkBeforeProceed = false)
    {
        if (!is_numeric($masterClass_id)) {
            return response()->json([
                'success' => false,
                'message' => "Invalid Master Class or Academic Year.",
            ], 422);
        }

        // Fetch master class with academic year
        $masterClass = MasterClass::with('academicYear')->find($masterClass_id);

        if (!$masterClass) {
            abort(404);
            // return response()->json([
            //     'success' => false,
            //     'message' => "Master Class does not exist.",
            // ], 422);
        }

        if ($checkBeforeProceed !== false) {
            if ($masterClass->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => "Master Class is inactive. Operations are not permitted.",
                ], 422);
            }

            if (!$masterClass->academicYear || $masterClass->academicYear->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => "Academic Year is inactive. Operations are not permitted.",
                ], 422);
            }
        }

        return true;
    }
}