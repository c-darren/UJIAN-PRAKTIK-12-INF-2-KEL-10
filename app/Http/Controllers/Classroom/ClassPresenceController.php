<?php

namespace App\Http\Controllers\Classroom;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Classroom\ClassPresence;
use App\Models\Classroom\ClassAttendance;
use App\Classroom\Traits\AuthorizesClassAccess;

class ClassPresenceController extends Controller
{
    use AuthorizesClassAccess;

    public function index($masterClass_id, $class_id, $attendance_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
    
        // Mengambil detail kelas
        $classList = ClassList::findOrFail($class_id);
    
        // Mengambil semua pengguna yang tergabung dalam kelas dan mengurutkan berdasarkan nama
        $users = $classList->students()->orderBy('name', 'asc')->get();
    
        // Mengambil data presensi berdasarkan attendance_id dengan eager loading 'student'
        $presences = ClassPresence::with('student')
                        ->where('attendance_id', $attendance_id)
                        ->get()
                        ->keyBy('user_id');
    
        // Memfilter presences untuk setiap status
        $izin = $presences->filter(function ($presence) {
            return $presence->status == 'Izin';
        });
        $sakit = $presences->filter(function ($presence) {
            return $presence->status == 'Sakit';
        });
        $alfa = $presences->filter(function ($presence) {
            return $presence->status == 'Alfa';
        });
    
        // Mengambil attendance dengan eager loading 'topic'
        $attendance = ClassAttendance::with('topic')->findOrFail($attendance_id);
    
        return view('dashboard.classroom.class_list.presence.main_view', [
            'page_title' => 'Class: ' . $classList->class_name . ' - Presensi Peserta Didik',
            'attendance' => $attendance,
            'users' => $users,
            'presences' => $presences,
            'izin' => $izin,
            'sakit' => $sakit,
            'alfa' => $alfa,
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
            'attendance_id' => $attendance_id,
        ]);
    }   
    
    public function bulkUpdate(Request $request, $masterClass_id, $class_id, $attendance_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);

        // Validasi input
        $request->validate([
            'presences' => 'required|array',
            'presences.*.user_id' => 'required|exists:users,id',
            'presences.*.status' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        DB::transaction(function () use ($request, $attendance_id) {
            foreach ($request->presences as $presenceData) {
                $userId = $presenceData['user_id'];
                $status = $presenceData['status'];

                // Update status presensi tanpa membuat entri baru
                $updated = DB::table('class_presences')
                            ->where('attendance_id', $attendance_id)
                            ->where('user_id', $userId)
                            ->update([
                                'status' => $status,
                                'updated_at' => now(),
                            ]);

                if ($updated === 0) {
                    // Jika tidak ada baris yang diperbarui, lempar exception untuk rollback
                    Log::warning("Presensi tidak ditemukan untuk user_id: {$userId} dan attendance_id: {$attendance_id}");
                    throw new \Exception("Presensi tidak ditemukan untuk user_id: {$userId}");
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil diperbarui.',
        ]);
    }
}