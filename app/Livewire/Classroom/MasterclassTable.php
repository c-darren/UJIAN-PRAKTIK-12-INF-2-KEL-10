<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\School\AcademicYear;
use App\Models\Classroom\MasterClass;

class MasterclassTable extends Component
{
    use WithPagination;

    public $search = '';
    public $academicYear = '';
    public $status = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $academicYears = [];
    
    public function mount()
    {
        $this->academicYears = AcademicYear::pluck('academic_year', 'id')->toArray();
    }

    // Metode untuk mengupdate filter academicYear
    public function updateAcademicYear($academicYearId)
    {
        $this->academicYear = $academicYearId;
        $this->resetPage();
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }
    // Mengatur properti sebagai query string untuk mendukung pagination, pencarian, dan sorting
    protected $queryString = [
        'search' => ['except' => ''],
        'academicYear' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'id'],
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
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
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
        $records = MasterClass::join('academic_years', 'master_classes.academic_year_id', '=', 'academic_years.id')
            ->when(trim($this->search), function($query) {
                $searchTerm = trim($this->search);
                $query->where('master_class_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('master_class_code', 'like', '%' . $searchTerm . '%');
            })
            ->when($this->academicYear, function($query) {
                $query->where('master_classes.academic_year_id', $this->academicYear);  // Menggunakan academic_year_id
            })
            ->when($this->status, function($query) {
                $query->where('master_classes.status', $this->status);
            })
            ->orderBy($this->sortField === 'academic_year' ? 'academic_years.academic_year' : 'master_classes.' . $this->sortField, $this->sortDirection)
            ->select('master_classes.*', 'academic_years.academic_year', 'academic_years.status as academic_year_status')
            ->paginate($this->perPage);
        
        return view('livewire.classroom.masterclass-table', [
            'records' => $records,
            'academicYears' => $this->academicYears,
        ]);
    }
}