<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Authentication\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        return view('authentication.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('authentication.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:App\Models\Role,name,NULL,id,deleted_at,NULL|max:255',
        ]);

        $role_name = $request->input('role_name');

        $role = Role::create([
            'name' => $role_name,
        ]);

        $permissions = $request->input('permissions');
        // dd($permissions);
        if ($permissions == null) {
            $role->rolepermission()->create([
                'role_id' => $role->id,
            ]);
        }
        foreach($permissions as $permission){
            $role->rolepermission()->create([
                'role_id' => $role->id,
                'permission_id' => $permission,
            ]);
        }
    
        return redirect()->route('admin.roles.create')->with('add_role_success', 'Added role has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $allpermissions = Permission::get();
        $yourpermissions = $role->rolepermission()->pluck('permission_id')->all();
        return view('authentication.roles.edit', compact('role', 'allpermissions', 'yourpermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->input('role_name'),
        ]);

        $checkedpermissions = $request->input('permissions');

        $allpermissions = $role->rolepermission()->pluck('permission_id')->all();
        $trashedpermissions = $role->rolepermission()->onlyTrashed()->pluck('permission_id')->all();

        if ($checkedpermissions == null) {
            $role->rolepermission()->delete();
        }

        else {
            foreach ($checkedpermissions as $checkedpermission){

                if ($role->rolepermission($checkedpermission)) {
                    if (in_array($checkedpermission, $trashedpermissions)) {
                        $role->rolepermission()->where('permission_id', $checkedpermission)->restore();
                    }
                }

                if (!(in_array($checkedpermission, $allpermissions))) {
                    if (in_array($checkedpermission, $trashedpermissions)) {
                        $role->rolepermission()->where('permission_id', $checkedpermission)->restore();
                    }
                    else {
                        $role->rolepermission()->create([
                            'role_id' => $role->id,
                            'permission_id' => $checkedpermission
                        ]);
                    }
                }
            }

            foreach ($allpermissions as $permission) {
                if (!(in_array($permission, $checkedpermissions))) {
                    $role->rolepermission()->where('permission_id', $permission)->delete();
                }
            }
        }

        return redirect()->route('admin.roles.index')->with('edit_role_success', 'Edit in role has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('edit_role_success', 'Role has been deleted.');
    }
}
