<?php

namespace App\ClassList\Student\Traits;

use App\Models\Classroom\ClassList;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;

trait AuthorizeAccess
{
    protected function CheckValidClass($masterClass_id, $enrollment_id = null, $userId = null)
    {
        try {
            // Cek master class dengan relasi students
            $masterClass = MasterClass::with(['students' => function($query) use ($enrollment_id, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } elseif ($enrollment_id) {
                    $query->where('id', $enrollment_id);
                }
            }])->where('status', 'Active')->find($masterClass_id);

            if (!$masterClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan atau tidak aktif'
                ], 422);
            }

            // Jika tidak perlu cek enrollment
            if (is_null($enrollment_id) && is_null($userId)) {
                return true;
            }

            // Cek enrollment
            if ($userId || $enrollment_id) {
                $hasEnrollment = $masterClass->students->isNotEmpty();
                if (!$hasEnrollment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pendaftaran peserta didik tidak ditemukan'
                    ], 422);
                }
            }

            return true;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validasi kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function CheckValidClassList($masterClass_id, $classList_id, $checkBeforeProceed = true)
    {
        // 1. Validate that masterClass_id and classList_id are numeric
        if (!is_numeric($masterClass_id) || !is_numeric($classList_id)) {
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
            if ($classList->enrollment_status !== 'Open') {
                return response()->json([
                    'success' => false,
                    'message' => "Enrollment status is Closed. Operations are not permitted.",
                ], 422);
            }

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
        return $classList;
    }

    protected function authorizeStudent($masterClass_id, $classList_id, $checkBeforeProceed = true)
    {
        $classList = $this->CheckValidClassList($masterClass_id, $classList_id, $checkBeforeProceed);
    
        if ($classList->students()->where('users.id', Auth::user()->id)->exists()) {
            // All checks passed
            return true;
        } else {
            return response()->json([
                'success' => false,
                'message' => "You are not enrolled to this class yet.",
            ], 422);
        }
    }

    protected function checkEnrollmentStudent($masterClass_id, $classList_id, $checkBeforeProceed = true)
    {
        $classList = $this->CheckValidClassList($masterClass_id, $classList_id, $checkBeforeProceed);
        
        // Jika classList bukan instance ClassList, berarti ada error
        if (!($classList instanceof ClassList)) {
            return $classList;
        }

        if ($classList->students()->where('users.id', Auth::user()->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah terdaftar di kelas ini.",
            ], 422);
        }

        // Return model ClassList jika valid
        return $classList;
    }

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
        
        $checkStudent = $classList->students()->where('user_id', Auth::user()->id)->exists();
        
        if (!$checkStudent) {
            abort(403, 'Unauthorized action. Student not assigned to this class.');
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
