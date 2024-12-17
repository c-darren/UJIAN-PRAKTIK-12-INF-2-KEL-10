<?php

namespace App\Classroom\Traits;

use App\Models\Classroom\ClassList;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;

trait AuthorizesClassAccess
{
    protected function authorizeAccess($role_id, $masterClass_id, $class_id, $checkBeforeProceed = true)
    {
        // Cek role
        if (Auth::user()->role_id != $role_id) {
            abort(403, 'Unauthorized action.');
        }

        // Cek keberadaan classList dan masterClass
        $classList = ClassList::where('id', $class_id)->first();
        $masterClass = MasterClass::where('id', $masterClass_id)->first();
        
        if (!$classList || !$masterClass) {
            abort(404, 'Class not found.');
        }
        
        $checkTeacher = $classList->teachers()->where('teacher_id', Auth::user()->id)->exists();
        
        if (!$checkTeacher) {
            abort(403, 'Unauthorized action. Teacher not assigned to this class.');
        }
        
        // Cek hubungan antara classList dan masterClass
        if ($classList->master_class_id !== $masterClass->id) {
            abort(403, 'ClassList does not belong to the specified MasterClass.');
        }

        if ($checkBeforeProceed && $masterClass->status !== 'Active') {
            return response()->json([
                'success' => false,
                'message' => 'Operation not permitted on an inactive MasterClass.',
            ]);
        }

        return true;
    }
}