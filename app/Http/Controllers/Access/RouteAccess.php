<?php

namespace App\Http\Controllers\Access;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Group\GroupList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Access\SetAccessRoute;

class RouteAccess extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = SetAccessRoute::with(['roles:id,role', 'groups:id,group_name', 'creator:id,name', 'editor:id,name'])->get();
        return $this->view('view_access', compact('routes'));
    }


    public function view($page_content = 'view_access', $data=[]){
        return view('dashboard.page_access.route.main_view', array_merge([
            'page_content' => $page_content,
        ], $data));
    }

    public function create(){
        $roles = $this->getAllRoles();
        $groups = $this->getAllGroups();
        $redirectUrl = route('admin.page_access.route.view');

        return $this->view('add_route_page_access', compact('roles', 'groups', 'redirectUrl'));;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_title' => 'required|string|max:255',
            'page_url' => 'required|string|max:255|unique:access_routes,page_url|regex:/^\/[\/\-_a-zA-Z0-9]+$/',
            'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE|max:10',
            'ip_address' => 'nullable|string|regex:/^(\*|\d{1,3})(\.(\*|\d{1,3})){3}$/',
            'type_ip_address' => 'required|string|in:Whitelist,Blacklist',
            'start_date' => 'required|date',
            'valid_until' => 'nullable|date|after:start_date',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:group_lists,id',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Enabled,Disabled',
            'type_group_list' => 'required|string|in:Whitelist,Blacklist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $route = new SetAccessRoute();
            $route->page_title = $request->input('page_title');
            $route->page_url = $request->input('page_url');
            $route->method = $request->input('method');
            $route->creator_id = Auth::user()->id;
            $route->ip_address = $request->input('ip_address');
            $route->type_ip_address = $request->input('type_ip_address');
            $route->status = $request->input('status');
            $route->start_date = $request->input('start_date');
            $route->valid_until = $request->input('valid_until');
            $route->type_group_list = $request->input('type_group_list');
            $route->description = $request->input('description');
            $route->saveQuietly();

            $route->roles()->sync($request->input('roles'));

            if ($request->has('groups')) {
                $route->groups()->sync($request->input('groups'));
            }

            return response()->json([
                'success' => true,
                'message' => 'You will be redirected to Route List in 2 seconds.',
                'redirect_url' => route('admin.page_access.route.view'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving route: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SetAccessRoute $SetAccessRoute = null, Request $request, $message = null, $messageError = null)
    {
        $allRoles = Role::select('id', 'role')->get();
        $allGroups = GroupList::select('id', 'group_name')->get();
    
        $routes = SetAccessRoute::with(['roles:id,role', 'groups:id,group_name', 'creator:id,name', 'editor:id,name'])->get();
    
        if ($SetAccessRoute == null && $routes->isEmpty()) {
            $routes = 'Data not found';
        }
    
        $view = $this->view('view_access', compact('routes', 'allRoles', 'allGroups'));
    
        if ($message) {
            $view->with('message', $message);
        }
    
        if ($messageError) {
            $view->with('messageError', $messageError);
        }
    
        return $view;
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!is_numeric($id) || $id == null) {
            return redirect()->route('admin.page_access.route.view')->with('messageError', [
                'title' => 'ID Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }
        $route = SetAccessRoute::with(['roles', 'groups', 'creator', 'editor'])->find($id);
        
        if ($route == null) {
            return redirect()->route('admin.page_access.route.view')->with('messageError', [
                'title' => 'ID: ' . $id . ' Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }else{
            $allRoles = $this->getAllRoles(['id', 'role']);
            $allGroups = $this->getAllGroups(['id', 'group_name']);
        };

        $redirectUrl = route('admin.page_access.route.view');
        return $this->view('edit_route_page_access', compact('route', 'allRoles', 'allGroups', 'redirectUrl'));
    }
    
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, SetAccessRoute $SetAccessRoute)
    {
        $id = $request->segment(count($request->segments()));

        $route = SetAccessRoute::find($id);

    
        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Route not found, is it already deleted?',
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'page_title' => 'required|string|max:255',
            'page_url' => 'required|string|max:255|unique:access_routes,page_url',
            'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE|max:10',
            'ip_address' => 'nullable|string|regex:/^(\*|\d{1,3})(\.(\*|\d{1,3})){3}$/',
            'type_ip_address' => 'required|string|in:Whitelist,Blacklist',
            'start_date' => 'required|date',
            'valid_until' => 'nullable|date|after:start_date',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:group_lists,id',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Enabled,Disabled',
            'type_group_list' => 'required|string|in:Whitelist,Blacklist',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
    
        try {
            $route->update([
                'page_title' => $request->input('page_title'),
                'page_url' => $request->input('page_url'),
                'method' => $request->input('method'),
                'editor_id' => Auth::user()->id,
                'ip_address' => $request->input('ip_address'),
                'type_ip_address' => $request->input('type_ip_address'),
                'status' => $request->input('status'),
                'start_date' => $request->input('start_date'),
                'valid_until' => $request->input('valid_until'),
                'type_group_list' => $request->input('type_group_list'),
                'description' => $request->input('description'),
            ]);

            $route->roles()->sync($request->input('roles'));

            if ($request->has('groups')) {
                $route->groups()->sync($request->input('groups'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully.',
                'redirectUrl' => route('admin.page_access.route.view'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update route: ' . $e->getMessage(),
            ], 500);
        }
    }
     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->segment(count($request->segments()));
    
        try {
            $deleted = SetAccessRoute::where('id', $id)->delete();
    
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Route deleted successfully',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found or already deleted',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting route: ' . $e->getMessage(),
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

    public function getAllGroups($column = null, $limit = null){
        if ($column == null && $limit == null) :
            $groups = GroupList::all();
        elseif ($column != null && $limit == null) :
            $groups = GroupList::select($column)->get();
        elseif ($column == null && $limit != null) :
            $groups = GroupList::limit($limit)->get();
        else :
            $groups = GroupList::select($column)->limit($limit)->get();
        endif;
        return $groups;
    }
}
