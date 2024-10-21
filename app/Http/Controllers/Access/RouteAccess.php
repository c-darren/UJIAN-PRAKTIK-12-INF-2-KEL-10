<?php

namespace App\Http\Controllers\Access;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Group\GroupList;
use App\Models\Access\SetAccessRoute;
use App\Http\Controllers\Controller;

class RouteAccess extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.page_access.route.main_view', [
            'page_content' => 'view_access'
        ]);
    }

    public function view($page_content = 'view_access', $data=[]){
        return view('dashboard.page_access.route.main_view', array_merge([
            'page_content' => $page_content,
        ], $data));
    }

    public function create(){
        $roles = $this->getAllRoles();
        $groups = $this->getAllGroups();
        return $this->view('add_route_page_access', compact('roles', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $SetAccessRoute = SetAccessRoute::create($request->all());
        $SetAccessRoute->roles()->sync($request->roles);
        $SetAccessRoute->groups()->sync($request->groups);
    }

    /**
     * Display the specified resource.
     */
    public function show(SetAccessRoute $SetAccessRoute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SetAccessRoute $SetAccessRoute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SetAccessRoute $SetAccessRoute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SetAccessRoute $SetAccessRoute)
    {
        //
    }

    public function getAllRoles(){
        $roles = Role::all();
        return $roles;
    }

    public function getAllGroups(){
        $groups = GroupList::all();
        return $groups;
    }
}
