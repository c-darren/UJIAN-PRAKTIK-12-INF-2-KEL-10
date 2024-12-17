<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom\MasterClass;

class DashboardController extends Controller
{
    public function index()
    {
        $emailData = $this->emailVerifyNotification();

        // Mendapatkan data kelas (Archived dan Active) sebagai array
        $classesData = $this->getClassTeachers(); // Array

        $data = [
            'page_title' => 'Dashboard',
        ];

        // Menggabungkan semua data
        // $emailData dan $classesData adalah array, sehingga bisa digabung dengan array_merge
        $mergedData = array_merge($data, $emailData, $classesData);

        return $this->view($mergedData);
    }
    
    public function emailVerifyNotification()
    {
        $user = Auth::user();
        $showVerificationAlert = is_null($user->email_verified_at);
        
        // Kembalikan array, bukan view
        return [
            'showVerificationAlert' => $showVerificationAlert,
            'user' => $user,
        ];
    }

    public function getClassTeachers()
    {
        $requestedTeacherId = Auth::id();
    
        // Mendapatkan kelas aktif
        $activeClasses = MasterClass::where('status', 'active')
            ->with(['classLists.teachers', 'classLists.subject'])
            ->get()
            ->map(function($masterClass) use ($requestedTeacherId) {
                // Filter class_lists yang memiliki teacher dengan teacher_id tertentu
                $filteredClassLists = $masterClass->classLists->map(function($classList) use ($requestedTeacherId) {
                    // Ambil semua teacher_id di classList ini
                    $teacherIds = $classList->teachers->pluck('id');
        
                    // Cek apakah teacher_id yang dicari terdapat dalam $teacherIds
                    if ($teacherIds->contains($requestedTeacherId)) {
                        return [
                            'master_class_id' => $classList->master_class_id,
                            'class_list_id' => $classList->id,
                            'class_name' => $classList->class_name,
                            'enrollment_status' => $classList->enrollment_status,
                            'teachers' => $classList->teachers->map(function($t) {
                                return $t->name;
                            })->unique()->values(),
                            'subject_id' => $classList->subject->id,
                            'subject' => $classList->subject->subject_name,
                        ];
                    }
    
                    return null;
                })->filter(function($classList) {
                    return !is_null($classList);
                });
    
                if ($filteredClassLists->isEmpty()) {
                    return null;
                }
    
                return [
                    'master_class_id' => $masterClass->id,
                    'status' => $masterClass->status,
                    'class_lists' => $filteredClassLists->values(),
                ];
            })
            ->filter(function($masterClass) {
                return !is_null($masterClass);
            })
            ->values();
    
        // Mendapatkan kelas yang diarsipkan
        $archivedClasses = MasterClass::where('status', 'archived')
            ->with(['classLists.teachers', 'classLists.subject'])
            ->get()
            ->map(function($masterClass) use ($requestedTeacherId) {
                $filteredClassLists = $masterClass->classLists->map(function($classList) use ($requestedTeacherId) {
                    $teacherIds = $classList->teachers->pluck('id');
    
                    if ($teacherIds->contains($requestedTeacherId)) {
                        return [
                            'master_class_id' => $classList->master_class_id,
                            'class_list_id' => $classList->id,
                            'class_name' => $classList->class_name,
                            'enrollment_status' => $classList->enrollment_status,
                            'teachers' => $classList->teachers->map(function($t) {
                                return $t->name;
                            })->unique()->values(),
                            'subject_id' => $classList->subject->id,
                            'subject' => $classList->subject->subject_name,
                        ];
                    }
    
                    return null;
                })->filter(function($classList) {
                    return !is_null($classList);
                });
    
                if ($filteredClassLists->isEmpty()) {
                    return null;
                }
    
                return [
                    'master_class_id' => $masterClass->id,
                    'status' => $masterClass->status,
                    'class_lists' => $filteredClassLists->values(),
                ];
            })
            ->filter(function($masterClass) {
                return !is_null($masterClass);
            })
            ->values();
    
        return [
            'activeClasses' => $activeClasses,
            'archivedClasses' => $archivedClasses,
        ];
    }    

    public function view(array $data = [])
    {
        if (auth()->user()->role_id == 3) {
            return redirect()->route('master-class.enrolled-class');
        }
        // dd($data);
        return view('dashboard.dashboard.dashboard', $data);
    }

}