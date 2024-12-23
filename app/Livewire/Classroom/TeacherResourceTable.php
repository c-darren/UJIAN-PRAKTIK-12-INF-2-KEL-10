<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Classroom\Material;
use App\Models\Classroom\Assignment;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class TeacherResourceTable extends Component
{
    use WithPagination;

    public $classList;
    public $classList_id;
    public $masterClass_id;
    public $topics;
    public $search = '';
    public $status = '';
    public $selectedTopic = 'all'; // Menyimpan topik yang dipilih
    public $sortLabel = 'teacher_id';
    public $sortDirection = 'asc';
    public $perPage = 5;
    

    public function mount($classList, $masterClass_id, $topics)
    {
        $this->classList = $classList;
        $this->classList_id = $classList->id;
        $this->masterClass_id = $masterClass_id;
        $this->topics = $topics;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function selectTopic($topic)
    {
        $this->selectedTopic = $topic;
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

    public function sortBy($field)
    {
        $fields = [
            'TeacherName' => 'author_name',
            'ResourceName' => 'resource_name',
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
        // Livewire secara otomatis akan memanggil render()
    }

    public function render()
    {
        $materials = Material::with(['topic','author','editor'])
            ->where('class_id', $this->classList_id)
            ->get()
            ->map(function($item) {
                $item->type = 'material';
                $item->resource_name = $item->material_name;
                $item->author_name = $item->author ? $item->author->name : '';
                $item->desc = strtolower($item->description ?? '');
                // Gunakan langsung $item->topic_id yang berasal dari tabel materials
                // Tidak perlu mengubah $item->id
                return $item;
            });
        
        $assignments = Assignment::with(['topic','author','editor'])
            ->where('class_id', $this->classList_id)
            ->get()
            ->map(function($item) {
                $item->type = 'assignment';
                $item->resource_name = $item->assignment_name;
                $item->author_name = $item->author ? $item->author->name : '';
                $item->desc = strtolower($item->description ?? '');
                // Gunakan langsung $item->topic_id yang berasal dari tabel assignments
                return $item;
            });
    
        // Filter berdasarkan status
        if ($this->status === 'materi') {
            $resources = $materials;
        } elseif ($this->status === 'tugas') {
            $resources = $assignments;
        } elseif ($this->status === 'deadline') {
            $resources = $assignments->filter(function($res) {
                return $res->end_date < now();
            });
        } else {
            $resources = $materials->merge($assignments);
        }
    
        // Filter berdasarkan pencarian
        if (trim($this->search) !== '') {
            $searchTerm = strtolower(trim($this->search));
            $resources = $resources->filter(function($res) use ($searchTerm) {
                return strpos($res->resource_name, $searchTerm) !== false
                    || strpos($res->author_name, $searchTerm) !== false
                    || strpos($res->desc, $searchTerm) !== false;
            });
        }
    
        // Filter berdasarkan topik jika bukan 'all'
        // Pastikan dropdown topik mengembalikan topic_id
        if ($this->selectedTopic !== 'all') {
            // $this->selectedTopic seharusnya topic_id (integer)
            $selectedTopicId = (int) $this->selectedTopic;
            $resources = $resources->filter(function($res) use ($selectedTopicId) {
                return $res->topic_id == $selectedTopicId;
            });
        }
    
        // Sorting
        $fields = [
            'TeacherName' => 'author_name',
            'ResourceName' => 'resource_name',
        ];
        
        $sortField = $fields[$this->sortLabel] ?? 'created_at';
        
        $resources = $resources->sortBy(function($res) use ($sortField) {
            return $res->{$sortField};
        }, SORT_REGULAR, $this->sortDirection === 'desc');
        
        // Pagination manual
        $currentPage = $this->getPage();
        $perPage = $this->perPage;
        $total = $resources->count();
        $items = $resources->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('livewire.classroom.teacher-resource-table', [
            'resources' => $paginator,
        ]);
    }
    
}