<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use App\Models\Classroom\ClassList;
use Livewire\WithPagination;

class TeacherTable extends Component
{
    use WithPagination;

    public $classList_id;
    public $masterClass_id;
    public $search = '';
    public $status = '';
    public $sortLabel = 'teacher_id';
    public $sortDirection = 'asc';
    public $perPage = 5;
    
    public function mount($classList_id, $masterClass_id)
    {
        $this->classList_id = $classList_id;
        $this->masterClass_id = $masterClass_id;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }
    // Mengatur properti sebagai query string untuk mendukung pagination, pencarian, dan sorting
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

    // Mengatur field dan arah sorting
    public function sortBy($field)
    {
        // Definisikan mapping antara label dan nama kolom sebenarnya
        $fields = [
            'TeacherName' => 'users.name',
        ];

        // Cek apakah field ada dalam mapping
        if (!array_key_exists($field, $fields)) {
            return; // Tidak melakukan apa-apa jika field tidak valid
        }

        // Jika sudah sorting berdasarkan field ini, toggle arah
        if ($this->sortLabel === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Jika belum, set ke field ini dan default ke ascending
            $this->sortLabel = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function autoUpdate()
    {
        // Livewire secara otomatis akan memanggil render(), jadi tidak perlu menambahkan kode tambahan di sini
    }

    public function render()
    {
        // Mapping label ke nama kolom yang diizinkan untuk sorting
        $mapping = [
            'TeacherName' => 'users.name',
        ];
    
        $sortField = $mapping[$this->sortLabel] ?? 'users.name';
        $sortDirection = in_array(strtolower($this->sortDirection), ['asc', 'desc']) ? strtolower($this->sortDirection) : 'asc';
    
        $records = ClassList::join('class_teachers', 'class_lists.id', '=', 'class_teachers.class_id')
            ->join('users', 'class_teachers.teacher_id', '=', 'users.id')
            ->where('class_teachers.class_id', $this->classList_id)
            ->when(trim($this->search), function($query) {
                $searchTerm = trim($this->search);
                $query->where('users.name', 'like', '%' . $searchTerm . '%');
            })
            ->select(
                'class_teachers.class_id as class_id',
                'class_teachers.teacher_id as teacher_id',
                'users.name as teacher_name'
            )
            ->orderBy($sortField, $sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.classroom.teacher-table', [
            'records' => $records,
        ]);
    }
}