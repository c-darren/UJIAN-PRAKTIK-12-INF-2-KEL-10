<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckUserRole;
class RoleController extends Controller
{
    protected $allowedRoleID;

    public function checkRole()
    {
        $allowedRoleId = 1;
        $sessionRoleId = session()->get('role_id');
    
        if ($sessionRoleId !== $allowedRoleId) {
            return redirect()->route('dashboard');
        };
        return null;
    }
    public function show()
    {
        $roles = Role::select('id', 'role', 'description')->get();
        return $this->view('view_roles', compact('roles'));
    }

    public function show_deleted()
    {
        $deletedRoles = Role::onlyTrashed()->get();
        return $this->view('view_roles', compact('deletedRoles'));
    }

    public function create()
    {
        $redirectUrl = route('admin.authentication.roles.view');
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
                'redirectUrl' => route('admin.authentication.roles.view')
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
            return redirect()->route('admin.authentication.roles.view')->with('messageError', [
                'title' => 'ID Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }
        $role = Role::select('id')->find($id);
        if ($role == null) {
            return redirect()->route('admin.authentication.roles.view')->with('messageError', [
                'title' => 'ID: ' . $id . ' Not Found',
                'message' => 'Make sure you have a valid id!',
            ]);
        };
        $role = Role::select('id', 'role', 'description')->find($id);
        $redirectUrl = route('admin.authentication.roles.view');
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
                'redirectUrl' => route('admin.authentication.roles.view')
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
                'redirectUrl' => route('admin.authentication.roles.view')
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
        return view("dashboard.authentication.roles.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
}