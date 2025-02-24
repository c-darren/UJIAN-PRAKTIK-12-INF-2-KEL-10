<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
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

        if(auth()->user()->role_id == 1) {
            // Get users with role and verification data
            $users = User::with(['role' => function ($query) {
                $query->whereNull('deleted_at');
            }])
            ->whereHas('role', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->select('id', 'name', 'username', 'email', 'email_verified_at', 'role_id', 'created_at')
            ->get();
        
            // Process data for charts
            $roleStats = $users->groupBy('role_id')
                ->map(function($group) {
                    return $group->count();
                });
        
            $verificationStats = [
                'verified' => $users->whereNotNull('email_verified_at')->count(),
                'unverified' => $users->whereNull('email_verified_at')->count()
            ];
        
            $mergedData = array_merge($data, $emailData, $classesData, [
                'roleStats' => $roleStats,
                'verificationStats' => $verificationStats
            ]);
        
            return $this->view($mergedData);
        } else {
            $mergedData = array_merge($data, $emailData, $classesData);
            return $this->view($mergedData);
        }
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
                            'master_class_name' => $classList->masterClass->master_class_name,
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
                            'master_class_name' => $classList->masterClass->master_class_name,
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