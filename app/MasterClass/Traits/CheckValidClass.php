<?php

namespace App\MasterClass\Traits;

use App\Models\Classroom\MasterClass;

trait CheckValidClass
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
}