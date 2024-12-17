<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Classroom\Traits\AuthorizesClassAccess;
use Illuminate\Http\Request;
use App\Models\Classroom\ClassList;
use App\Models\Classroom\MasterClass;

class ClassInfoController extends Controller
{
    use AuthorizesClassAccess;

    public function showClassInfo($masterClass_id, $class_id)
    {
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);

        $info = $this->class_info($masterClass_id, $class_id);

        return view('dashboard.classroom.class_list.class_info', $info);
    }

    private function class_info($masterClass_id, $class_id)
    {
        $masterClass = MasterClass::where('id', $masterClass_id)->firstOrFail();

        $classList = ClassList::where('id', $class_id)
            ->with('subject')
            ->firstOrFail();

        $subject_name = $classList->subject ? $classList->subject->subject_name : null;

        return [
            'masterClass_id' => $masterClass->id,
            'masterClass_name' => $masterClass->master_class_name ?? $masterClass->class_name, // Sesuaikan dengan kolom database
            'master_class_status' => $masterClass->status,
            'classList' => $classList,
            'page_title' => $classList->class_name,
            'subject_name' => $subject_name,
        ];
    }
}