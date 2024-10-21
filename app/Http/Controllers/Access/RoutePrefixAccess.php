<?php

namespace App\Http\Controllers\Access;

use App\Models\Auth\Role;
use Illuminate\Http\Request;
use App\Models\Group\GroupList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Access\SetAccessRoutePrefix;

class RoutePrefixAccess extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prefixes = SetAccessRoutePrefix::with(['roles', 'groups'])->get();
        return $this->view('view_access', compact('prefixes'));
    }


    public function view($page_content = 'view_access', $data=[]){
        return view('dashboard.page_access.route_prefix.main_view', array_merge([
            'page_content' => $page_content,
        ], $data));
    }

    public function create(){
        $roles = $this->getAllRoles();
        $groups = $this->getAllGroups();
        return $this->view('add_route_prefix_page_access', compact('roles', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prefix_name' => [
                'required',
                'string',
                'max:255'
            ],
            'prefix_url' => [
                'required',
                'string',
                'max:255',
                'unique:access_routes_prefixes,prefix',
                'regex:/^\/[\/\-_a-zA-Z0-9]+\/$/'
            ],
            'ip_address' => [
                'nullable',
                'string',
                'regex:/^(\*|\d{1,3})(\.(\*|\d{1,3})){3}$/',
            ],

            'type_ip_address' => [
                'required',
                'string',
                'in:Whitelist,Blacklist'
            ],
            'start_date' => ['required','date'],
            'valid_until' => ['nullable','date','after:start_date'],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:group_lists,id',
            'description' => 'nullable|string',
            'status' => ['required','string','in:Enabled,Disabled'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $routePrefix = new SetAccessRoutePrefix();
            $routePrefix->name = $request->input('prefix_name');
            $routePrefix->prefix = $request->input('prefix_url');
            $routePrefix->creator_id = Auth::user()->id;
            $routePrefix->ip_address = $request->input('ip_address');
            $routePrefix->type_ip_address = $request->input('type_ip_address');
            $routePrefix->status = $request->input('status');
            $routePrefix->start_date = $request->input('start_date');
            $routePrefix->valid_until = $request->input('valid_until');
            $routePrefix->type_group_list = $request->input('type_group_list');
            $routePrefix->description = $request->input('description');
            $routePrefix->save();

            $routePrefix->roles()->sync($request->input('roles'));

            if ($request->has('groups')) {
                $routePrefix->groups()->sync($request->input('groups'));
            }

            return response()->json([
                'success' => true,
                'redirect_url' => route('admin.page_access.route_prefix.view'),
                'message' => 'Route prefix created successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while saving route prefix: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SetAccessRoutePrefix $setAccessRoutePrefix, $message = null, $messageError = null)
    {
        $allRoles = Role::select('id', 'role')->get();
        $allGroups = GroupList::select('id', 'group_name')->get();
        $prefixes = SetAccessRoutePrefix::with(['roles', 'groups', 'creator', 'editor'])->get();
        
        if ($setAccessRoutePrefix == null) {
            $prefixes = 'Data not found';
        }
    
        $view = $this->view('view_access', compact('prefixes', 'allRoles', 'allGroups'));
    
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
            return redirect()->route('admin.page_access.route_prefix.view')->with('messageError', [
                'title' => 'ID Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }
        $prefix = SetAccessRoutePrefix::with(['roles', 'groups', 'creator', 'editor'])->find($id);
        
        if ($prefix == null) {
            return redirect()->route('admin.page_access.route_prefix.view')->with('messageError', [
                'title' => 'ID: ' . $id . ' Not Found',
                'message' => 'Make sure you have a valid id!'
            ]);
        }else{
            $allRoles = $this->getAllRoles(['id', 'role']);
            $allGroups = $this->getAllGroups(['id', 'group_name']);
        };

        $redirectUrl = route('admin.page_access.route_prefix.view');
        return $this->view('edit_route_prefix_page_access', compact('prefix', 'allRoles', 'allGroups', 'redirectUrl'));
    }
    
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, SetAccessRoutePrefix $setAccessRoutePrefix)
    {
        $id = $request->segment(count($request->segments()));
    
        $setAccessRoutePrefix = SetAccessRoutePrefix::find($id);
    
        if (!$setAccessRoutePrefix) {
            return response()->json([
                'success' => false,
                'message' => 'Error: Route prefix not found, is it already deleted?',
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'prefix_name' => 'required|string|max:255',
            'prefix_url' => 'required|string|max:255|regex:/^\/[\/\-_a-zA-Z0-9]+\/$/',
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
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
    
        try {
            $setAccessRoutePrefix->name = $request->input('prefix_name');
            $setAccessRoutePrefix->prefix = $request->input('prefix_url');
            $setAccessRoutePrefix->editor_id = Auth::user()->id;
            $setAccessRoutePrefix->ip_address = $request->input('ip_address');
            $setAccessRoutePrefix->type_ip_address = $request->input('type_ip_address');
            $setAccessRoutePrefix->status = $request->input('status');
            $setAccessRoutePrefix->start_date = $request->input('start_date');
            $setAccessRoutePrefix->valid_until = $request->input('valid_until');
            $setAccessRoutePrefix->type_group_list = $request->input('type_group_list');
            $setAccessRoutePrefix->description = $request->input('description');
            $setAccessRoutePrefix->save();
    
            $setAccessRoutePrefix->roles()->sync($request->input('roles'));
    
            if ($request->has('groups')) {
                $setAccessRoutePrefix->groups()->sync($request->input('groups'));
            }
    
            return response()->json([
                'success' => true,
                'message' => 'You will be redirected to Route Prefix Lists in 2 seconds.',
                'redirectUrl' => route('admin.page_access.route_prefix.view'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update route prefix: ' . $e->getMessage(),
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
            $deleted = SetAccessRoutePrefix::where('id', $id)->delete();
    
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Route prefix deleted successfully',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Route prefix not found or already deleted',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting route prefix: ' . $e->getMessage(),
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
