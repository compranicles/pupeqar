<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Authentication\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = Permission::get();
        return view('authentication.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Permission::class);

        return view('authentication.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);

        $request->validate([
            'permission_name' => 'required|unique:App\Models\Authentication\Permission,name,NULL,id,deleted_at,NULL|max:255',
        ]);

        Permission::create([
            'name' => $request->input('permission_name'),
        ]);

        return redirect()->route('admin.permissions.create')->with('add_permission_success', 'Added permission has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Permission::class);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', Permission::class);

        return view('authentication.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', Permission::class);

        $request->validate([
            'permission_name' => ['required', 'unique:App\Models\Authentication\Permission,name,NULL,id,deleted_at,NULL', 'max:255'],
        ]);

        $permission->update([
            'name'=>$request->input('permission_name'),
        ]);

        return redirect()->route('admin.permissions.index')->with('edit_permission_success', 'Edited permission has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', Permission::class);

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('edit_permission_success', 'Permission has been deleted.');
    }
}
