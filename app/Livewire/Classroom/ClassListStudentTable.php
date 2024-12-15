<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Classroom\ClassList;
use App\Models\Classroom\MasterClassStudents;
use Illuminate\Support\Facades\DB;

class ClassListStudentTable extends Component
{
    use WithPagination;

    public $masterClass_id;
    public $classList_id;
    public $className;
    public $search = '';
    public $status = '';
    public $studentCategory = '2'; // Mengganti nama properti
    public $sortLabel = 'StudentName';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public function mount($masterClass_id, $classList_id, $classListName)
    {
        $this->masterClass_id = $masterClass_id;
        $this->classList_id = $classList_id;
        $this->className = $classListName;
        // Set default kategori ke '2'
        $this->studentCategory = '2';
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortLabel' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Mengganti nama metode untuk menghindari konflik
    public function updateStudentCategory($category)
    {
        $this->studentCategory = $category;
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $fields = [
            'id' => 'master_class_students.id',
            'StudentName' => 'users.name',
            'EnrolledStatus' => 'master_class_students.status',
        ];

        if (!array_key_exists($field, $fields)) {
            return;
        }

        if ($this->sortLabel === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortLabel = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function autoUpdate()
    {
        // Livewire secara otomatis memanggil render(), tidak perlu implementasi tambahan
    }

    public function render()
    {
        $mapping = [
            'StudentName' => 'users.name',
        ];
    
        $sortField = $mapping[$this->sortLabel] ?? 'users.name';
        $sortDirection = in_array(strtolower($this->sortDirection), ['asc', 'desc']) ? strtolower($this->sortDirection) : 'asc';
    
        switch ($this->studentCategory) {
            case '1':
                // All students in master_class_students
                $records = MasterClassStudents::join('users', 'master_class_students.user_id', '=', 'users.id')
                    ->where('master_class_students.master_class_id', $this->masterClass_id)
                    ->when(trim($this->search), function($query) {
                        $searchTerm = trim($this->search);
                        $query->where('users.name', 'like', '%' . $searchTerm . '%');
                    })
                    ->select(
                        'master_class_students.master_class_id as master_class_id',
                        'master_class_students.user_id as student_id',
                        'users.name as student_name'
                    )
                    ->orderBy($sortField, $sortDirection)
                    ->paginate($this->perPage);
                break;
    
            case '2':
                // Students in this class_list
                $records = ClassList::join('class_students', 'class_lists.id', '=', 'class_students.class_id')
                    ->join('users', 'class_students.user_id', '=', 'users.id')
                    ->where('class_students.class_id', $this->classList_id)
                    ->when(trim($this->search), function($query) {
                        $searchTerm = trim($this->search);
                        $query->where('users.name', 'like', '%' . $searchTerm . '%');
                    })
                    ->select(
                        'class_students.class_id as class_id',
                        'class_students.user_id as student_id',
                        'users.name as student_name'
                    )
                    ->orderBy($sortField, $sortDirection)
                    ->paginate($this->perPage);
                break;
    
            case '3':
                // Students not in this class_list
                $records = MasterClassStudents::join('users', 'master_class_students.user_id', '=', 'users.id')
                    ->where('master_class_students.master_class_id', $this->masterClass_id)
                    ->whereNotExists(function($query) {
                        $query->select(DB::raw(1))
                            ->from('class_students')
                            ->whereColumn('class_students.user_id', 'master_class_students.user_id')
                            ->where('class_students.class_id', $this->classList_id);
                    })
                    ->when(trim($this->search), function($query) {
                        $searchTerm = trim($this->search);
                        $query->where('users.name', 'like', '%' . $searchTerm . '%');
                    })
                    ->select(
                        'master_class_students.master_class_id as master_class_id',
                        'master_class_students.user_id as student_id',
                        'users.name as student_name'
                    )
                    ->orderBy($sortField, $sortDirection)
                    ->paginate($this->perPage);
                break;
    
            default:
                // Default to category 2
                $records = ClassList::join('class_students', 'class_lists.id', '=', 'class_students.class_id')
                    ->join('users', 'class_students.user_id', '=', 'users.id')
                    ->where('class_students.class_id', $this->classList_id)
                    ->when(trim($this->search), function($query) {
                        $searchTerm = trim($this->search);
                        $query->where('users.name', 'like', '%' . $searchTerm . '%');
                    })
                    ->select(
                        'class_students.class_id as class_id',
                        'class_students.user_id as student_id',
                        'users.name as student_name'
                    )
                    ->orderBy($sortField, $sortDirection)
                    ->paginate($this->perPage);
                break;
        }
    
        if ($this->studentCategory !== '2') {
            // Ambil semua user_id yang sudah ditugaskan di class_list_id tertentu
            $assignedStudentIds = DB::table('class_students')
                ->where('class_id', $this->classList_id)
                ->pluck('user_id')
                ->toArray();
    
            // Transformasi koleksi untuk menambahkan is_assigned
            $records->getCollection()->transform(function ($record) use ($assignedStudentIds) {
                $record->is_assigned = in_array($record->student_id, $assignedStudentIds);
                return $record;
            });
        } else {
            // Untuk kategori 2, semua peserta didik sudah ditugaskan
            $records->getCollection()->transform(function ($record) {
                $record->is_assigned = true;
                return $record;
            });
        }
    
        return view('livewire.classroom.class-list-student-table', [
            'records' => $records,
        ]);
    }
}