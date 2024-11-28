<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 5;

    // Mengatur properti sebagai query string untuk mendukung pagination, pencarian, dan sorting
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }
    // Reset halaman ketika pencarian diubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Mengatur field dan arah sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle arah sort
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Set field baru dan arah sort default
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    // Fungsi untuk melakukan auto-update data setiap 10 detik
    public function autoUpdate()
    {
        // Livewire secara otomatis akan memanggil render(), jadi tidak perlu menambahkan kode tambahan di sini
    }

    // Mendengarkan event auto-update
    protected $listeners = [
        'auto-update' => 'autoUpdate',
    ];

    public function render()
    {
        $dataSubject = Subject::query()
            ->when(trim($this->search), function($query) {
                $searchTerm = trim($this->search);
                $query->where('subject_name', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.classroom.subject-table', [
            'dataSubject' => $dataSubject,
        ]);
    }

}
