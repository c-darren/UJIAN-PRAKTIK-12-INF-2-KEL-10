<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.authentication.users.view', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'role')->get();
        $redirectUrl = route('admin.authentication.users.view');
        return $this->view('add_users', compact('roles', 'redirectUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255|unique:users,username',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'role_id'               => 'required|exists:roles,id',
            'avatar'                => 'nullable|image|mimes:jpeg,png,gif|max:5120', // 5MB
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            $avatarPath = null;
        }

        $user = User::create([
            'name'              => $validatedData['name'],
            'username'          => $validatedData['username'],
            'avatar'            => $avatarPath,
            'role_id'           => $validatedData['role_id'],
            'email'             => $validatedData['email'],
            'email_verified_at' => null,
            'password'          => Hash::make($validatedData['password']),
            'remember_token'    => Str::random(60),
        ]);
        $user->updated_at = null;
        $user->saveQuietly();

        if ($user) {
            return response()->json([
                'success'   => true,
                'message'   => 'User created successfully.',
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to create user.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $users = User::with(['role' => function ($query) {
            $query->whereNull('deleted_at');
        }])
        ->whereHas('role', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->select('id', 'name', 'username', 'email', 'email_verified_at', 'role_id', 'avatar', 'created_at', 'updated_at')
        ->get();
        $roles = Role::select('id', 'role')->get();
        return $this->view('view_users', compact('users', 'roles'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $validatedData = $request->validate([
            'fullName'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'roleId'   => 'required|exists:roles,id',
            'avatar'    => 'nullable|image|mimes:jpeg,png,gif|max:5120', 
        ]);
    
        // Check if delete avatar is checked
        if ($request->input('deleteAvatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
    
        $user->name = $validatedData['fullName'];
        $user->username = $validatedData['username'];
        if ($user->email !== $validatedData['email']) {
            $user->email_verified_at = null; // Set email_verified_at to null if email is changed
        }        
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['roleId'];

        // Check if email has changed, and if so, remove email verification timestamp
    
        if ($request->input('resetPassword')) {
            $user->password = Hash::make('password');
        }
    
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()->json([
                'success'   => true,
                'message'   => 'User deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to delete user.',
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
    
            return response()->json([
                'success' => true,
                'message' => 'User successfully restored'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while restoring role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAllRoles($column = null, $limit = null){
        if ($column == null && $limit == null) :
            $roles = Role::all();
        elseif ($column != null && $limit == null) :
            $roles = Role::select($column)->get();
        elseif ($column == null && $limit != null) :
            $roles = Role::limit($limit)->get();
        else :
            $roles = Role::select($column)->limit($limit)->get();
        endif;
        return $roles;
    }

    protected function view($page_content = 'view_users', $data = [])
    {
        return view("dashboard.authentication.users.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}
