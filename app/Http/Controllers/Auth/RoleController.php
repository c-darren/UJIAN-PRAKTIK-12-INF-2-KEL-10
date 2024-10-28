<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next){
            $response = $this->checkRole();
            return $response ?: $next($request);
        });
    }
    public function checkRole()
    {
        $allowedRoleId = 1; // Adjust this to the allowed role ID as needed
        $sessionRoleId = session()->get('role_id');
    
        if ($sessionRoleId !== $allowedRoleId) {
            return redirect()->route('dashboard'); // Redirect if role IDs don't match
        };
        return null;
    }
    public function show()
    {
        $roles = Role::select()->get();
        return $this->view('view_roles', compact('roles'));
    }

    public function show_deleted()
    {
        $deletedRoles = Role::onlyTrashed()->get();
        return $this->view('view_roles', compact('deletedRoles'));
    }

    public function create()
    {
        $redirectUrl = route('admin.authentication.role.view');
        return $this->view('add_roles', compact('redirectUrl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Role::create([
                'role' => $request->input('role'),
                'description' => $request->input('description'),
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Role successfully saved.',
                'redirectUrl' => route('admin.authentication.role.view')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id) || $id == null) {
            return redirect()->route('admin.authentication.role.view')->with('messageError', [
                'title' => 'ID Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }
        $role = Role::select('id')->find($id);
        if ($role == null) {
            return redirect()->route('admin.authentication.role.view')->with('messageError', [
                'title' => 'ID: ' . $id . ' Not Found',
                'message' => 'Make sure you have a valid id!',
            ]);
        };
        $role = Role::findOrFail($id);
        $redirectUrl = route('admin.authentication.role.view');
        return $this->view('edit_roles', compact('role', 'redirectUrl'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $role->update([
                'role' => $request->input('role'),
                'description' => $request->input('description'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'You will be redirected to Role List in 2 seconds',
                'redirectUrl' => route('admin.authentication.role.view')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while update roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Role successfully deleted',
                'redirectUrl' => route('admin.authentication.role.view')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while deleting role: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);
            $role->restore();
    
            return response()->json([
                'success' => true,
                'message' => 'Role successfully restored'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while restoring role: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function view($page_content = 'view_roles', $data = [])
    {
        return view("dashboard.page_access.role.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}