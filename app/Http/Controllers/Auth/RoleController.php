<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function show()
    {
        // $roles = Role::select('id', 'role', 'description')->get();
        // RolesUpdated::dispatch($roles);
        return $this->view('view_roles');
    }

    public function show_deleted()
    {
        $deletedRoles = Role::onlyTrashed()->get();
        return $this->view('view_roles', compact('deletedRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|max:255',
            'desc' => 'required|string',
        ]);

        try {
            $role = new Role();
            $role->role = $request->input('roleName');
            $role->description = $request->input('desc');
            $role->updated_at = null;
            $role->save();
        
            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'roleName' => 'required|string|max:255',
            'roleDesc' => 'required|string',
        ]);

        try {
            $role->update([
                'role' => $request->input('roleName'),
                'description' => $request->input('roleDesc'),
            ]);
            return response()->json([
                'success' => true,
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