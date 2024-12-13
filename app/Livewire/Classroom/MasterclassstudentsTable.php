<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Classroom\MasterClassStudents;

class MasterclassstudentsTable extends Component
{
    use WithPagination;

    public $masterClass_id;
    public $search = '';
    public $status = '';
    public $sortLabel = 'EnrolledStatus';
    public $sortDirection = 'asc';
    public $perPage = 5;
    
    public function mount($masterClass_id)
    {
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
            'id' => 'master_class_students.id',
            'StudentName' => 'users.name',
            'EnrolledStatus' => 'master_class_students.status',
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
        // Mapping label ke nama kolom
        $mapping = [
            'id' => 'master_class_students.id',
            'StudentName' => 'users.name',
            'EnrolledStatus' => 'master_class_students.status',
        ];

        $sortField = $mapping[$this->sortLabel] ?? 'master_class_students.status';

        $records = MasterClassStudents::join('users', 'master_class_students.user_id', '=', 'users.id')
            ->where('master_class_students.master_class_id', $this->masterClass_id)
            ->when(trim($this->search), function($query) {
                $searchTerm = trim($this->search);
                $query->where('users.name', 'like', '%' . $searchTerm . '%');
            })
            ->when($this->status, function($query) {
                $query->where('master_class_students.status', $this->status);
            })
            ->select(
                'master_class_students.id as id',
                'users.name as name',
                'master_class_students.status as status'
            )
            ->orderBy($sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.classroom.masterclass_students-table', [
            'records' => $records,
        ]);
    }
}