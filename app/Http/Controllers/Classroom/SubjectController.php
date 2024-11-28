<?php

namespace App\Http\Controllers\Classroom;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Classroom\Subject;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function show()
    {
        return $this->view('view_subjects');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => [
                'required',
                'string',
                'max:255',
                'unique:subjects,subject_name',
            ],
        ]);
    
        try {
            $subjects = new Subject();
            $subjects->subject_name = $request->input('subject_name');
            $subjects->save();
        
            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving subject: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $subjects = Subject::findOrFail($id);
    
        $request->validate([
            'subject_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'subject_name')->ignore($id),
            ],
        ]);
    
        try {
            $subjects->update([
                'subject_name' => $request->input('subject_name'),
            ]);
            return response()->json([
                'success' => true,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while update subject: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $subjects = Subject::findOrFail($id);
            $subjects->delete();
    
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while deleting subject: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function view($page_content = 'view_subjects', $data = [])
    {
        return view("dashboard.classroom.subject.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}
