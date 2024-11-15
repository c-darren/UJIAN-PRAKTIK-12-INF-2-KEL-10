<?php
namespace App\Livewire\Authentication;

use Livewire\Component;
use App\Models\Auth\User;
use Livewire\WithPagination;
use App\Models\Auth\Role; // Pastikan Role sudah di-import

class UsersTable extends Component
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
        // Menggunakan eager loading untuk relasi 'role'
        $users = User::query()
            ->select('users.id', 'users.name', 'users.username', 'users.email', 'users.email_verified_at', 'users.role_id', 'users.avatar', 'users.created_at', 'users.updated_at')
            ->with('role')  // Eager load relasi role
            ->when(trim($this->search), function($query) {
                $query->where('users.name', 'like', '%' . trim($this->search) . '%')
                      ->orWhere('users.username', 'like', '%' . trim($this->search) . '%')
                      ->orWhere('users.email', 'like', '%' . trim($this->search) . '%');
            })
            ->orderBy($this->sortField === 'role' ? 'roles.role' : $this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.authentication.users-table', [
            'users' => $users,
        ]);
    }
}
