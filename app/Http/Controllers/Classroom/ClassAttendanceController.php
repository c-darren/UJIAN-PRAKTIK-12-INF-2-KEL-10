<?php

namespace App\Http\Controllers\Classroom;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Classroom\Topic;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use App\Models\Classroom\ClassPresence;
use App\Models\Classroom\ClassAttendance;
use App\Classroom\Traits\AuthorizesClassAccess;

class ClassAttendanceController extends Controller
{
    use AuthorizesClassAccess;

    public function index($masterClass_id, $classList_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $classList_id, false);

        // Mengambil semua topik terkait kelas
        $topics = Topic::where('class_id', $classList_id)->get();

        // Mengambil detail kelas
        $classList = ClassList::findOrFail($classList_id);

        // Mengambil semua attendance terkait kelas beserta topiknya
        $attendances = ClassAttendance::where('class_id', $classList_id)
            ->with('topic')
            ->orderBy('attendance_date', 'desc')
            ->get();
        
        // Memformat tanggal untuk setiap attendance
        $attendances->transform(function ($attendance) {
            $attendance->formattedDate = Carbon::parse($attendance->attendance_date)
                ->locale('id')
                ->isoFormat('dddd, D MMMM YYYY HH:mm');
            return $attendance;
        });

        return view('dashboard.classroom.class_list.attendee.main_view', [
            'page_title' => 'Class: ' . $classList->class_name . ' - Attendance Management',
            'topics' => $topics,
            'attendances' => $attendances,
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
        ]);
    }

    public function store(Request $request, $masterClass_id, $classList_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $classList_id, true);
    
        // Validasi input
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'attendance_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);
    
        $attendance = null;
        DB::transaction(function () use ($request, $classList_id, &$attendance) {
            $attendance = ClassAttendance::create([
                'class_id' => $classList_id,
                'topic_id' => $request->topic_id,
                'attendance_date' => $request->attendance_date,
                'description' => $request->description,
                'updated_at' => null,
            ]);
    
            $studentIds = ClassList::find($classList_id)->students()->pluck('user_id')->toArray();
    
            $presences = [];
            foreach ($studentIds as $studentId) {
                $presences[] = [
                    'attendance_id' => $attendance->id,
                    'user_id' => $studentId,
                    'status' => 'Hadir',
                    'created_at' => now(),
                ];
            }
    
            // Menambahkan presensi ke database
            ClassPresence::insert($presences);
        });
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat daftar kehadiran kelas.',
            'redirectUrl' => route('classroom.presence.index', [$masterClass_id, $classList_id, $attendance->id])
        ]);
    }
    

    public function update(Request $request, $masterClass_id, $classList_id, $attendance_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $classList_id, true);

        // Validasi input
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'attendance_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Mencari record attendance yang akan diperbarui
        $attendance = ClassAttendance::where('id', $attendance_id)
            ->where('class_id', $classList_id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $attendance) {
            // Memperbarui record attendance
            $attendance->update([
                'topic_id' => $request->topic_id,
                'attendance_date' => $request->attendance_date,
                'description' => $request->description,
            ]);

            // Presensi tidak diupdate secara otomatis
            // Guru dapat memperbarui status presensi melalui fitur terpisah
        });

        return response()->json([
            'success' => true,
            'message' => 'Berhasil memperbarui daftar kehadiran kelas.',
        ]);
    }

    public function destroy($masterClass_id, $classList_id, $attendance_id)
    {
        // Verifikasi user role dan status masterClass
        $this->authorizeAccess(2, $masterClass_id, $classList_id, true);

        // Mencari record attendance yang akan dihapus
        $attendance = ClassAttendance::where('id', $attendance_id)
            ->where('class_id', $classList_id)
            ->firstOrFail();
        
        // Menghapus record attendance beserta presensinya
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus daftar kehadiran kelas.',
        ]);
    }
}