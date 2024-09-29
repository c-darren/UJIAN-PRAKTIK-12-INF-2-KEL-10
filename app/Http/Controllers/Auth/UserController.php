<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::with('role')->get();
        return view('auth.users.index', compact('users'));
    }

    // Menampilkan form untuk membuat user baru
    public function create()
    {
        $roles = Role::all();
        return view('auth.users.create', compact('roles'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi dan penyimpanan data
        $validatedData = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    // Metode lain seperti show, edit, update, destroy dapat diimplementasikan serupa
}
