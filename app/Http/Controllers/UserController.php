<?php

namespace App\Http\Controllers;

use App\Models\Dean;
// use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use App\Models\Invite;
use App\Models\Chairperson;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\Permission;
use App\Notifications\InviteNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Authentication\RolePermission;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::get();
        $permissions = Permission::get();
        $departments = Department::select('departments.*', 'colleges.name as college_name')->join('colleges', 'departments.college_id', 'colleges.id')->get();
        $colleges = College::all();
        return view('users.create', compact('roles', 'permissions', 'departments', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix') ?? null,
            'date_of_birth' => $request->input('birthdate'),
            // 'role_id' => 1,
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user_id = $user->id;

        $roles = $request->input('roles');
        foreach($roles as $role){
            $user->userrole()->create([
                'user_id' => $user_id,
                'role_id' => $role,
            ]);
        }
        
        if($request->has('department')){
            Chairperson::create([
                'user_id' => $user_id,
                'department_id' => $request->input('department') ?? null
            ]);
        }
        if($request->has('college')){
            Dean::create([
                'user_id' => $user_id,
                'college_id' => $request->input('college') ?? null
            ]);
        }
        return redirect()->route('admin.users.create')->with('add_user_success','User added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);

        $users = User::all();
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', User::class);

        // $rolehaspermissions = RolePermission::join('user_roles', 'user_roles.role_id', '=', 'role_permissions.role_id')
        //                                 ->where('user_roles.user_id', '=', $user->id)
        //                                 ->select('role_permissions.role_id')
        //                                 ->get();
        $roles = Role::get();
        $yourroles = $user->userrole()->pluck('role_id')->all();
        $permissions = Permission::get();
        $departments = Department::select('departments.*', 'colleges.name as college_name')->join('colleges', 'departments.college_id', 'colleges.id')->get();
        $chairperson = Chairperson::where('user_id', $user->id)->pluck('id')->first();

        return view('users.edit', compact('user', 'roles', 'permissions', 'yourroles', 'departments', 'chairperson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', User::class);

        $checkedroles = $request->input('roles');

        $allroles = $user->userrole()->pluck('role_id')->all();
        $trashedroles = $user->userrole()->onlyTrashed()->pluck('role_id')->all();

        if ($checkedroles == null) {
            $user->userrole()->delete();
        }

        else {
            foreach ($checkedroles as $checkedrole){

                if ($user->userrole($checkedrole)) {
                    if (in_array($checkedrole, $trashedroles)) {
                        $user->userrole()->where('role_id', $checkedrole)->restore();
                    }
                }

                if (!(in_array($checkedrole, $allroles))) {
                    if (in_array($checkedrole, $trashedroles)) {
                        $user->userrole()->where('role_id', $checkedrole)->restore();
                    }
                    else {
                        $user->userrole()->create([
                            'user_id' => $user->id,
                            'role_id' => $checkedrole
                        ]);
                    }
                }
            }

            foreach ($allroles as $role) {
                if (!(in_array($role, $checkedroles))) {
                    $user->userrole()->where('role_id', $role)->delete();
                }
            }
        }

        Chairperson::where('user_id', $user->id)->update([
            'department_id' => $request->input('department') ?? null
        ]);
        Dean::where('user_id', $user->id)->update([
            'college_id' => $request->input('college') ?? null
        ]);


        return redirect()->route('admin.users.index')->with('edit_user_success','User record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user->delete();

        return redirect()->route('admin.users.index')->with('edit_user_success', 'User record deleted successfully');
    }

    // public function invite(){
    //     $invites = Invite::all();
    //     return view('users.invite', compact('invites'));
    // }

    // public function send(Request $request){
    //     $request->validate([
    //         'email' => ['required', 'email', 'max:255', 'unique:users', 'unique:invites'],
    //     ]);
    //     do{
    //         $token = Str::random(20);
    //     } while (Invite::where('token', $token)->first());

    //     Invite::create([
    //         'token' => $token,
    //         'email' => $request->input('email'),
    //         'status' => 0,
    //     ]);

    //     $url = URL::temporarySignedRoute(
    //         'registration', now()->addMinutes(300), ['token' => $token]
    //     );
    //     Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
    //     return redirect()->route('admin.users.invite')->with('success', 'User invited successfully');
    // }

    // public function registration_view($token){
    //     $invite = Invite::where('token', $token)->first();
    //     if($invite === null){
    //         return abort("403");
    //     }
    //     elseif($invite->status != 0){
    //         return redirect()->route('login')->with('register-error','Email already registered');
    //     }
    //     return view('auth.register', ['invite' => $invite]);
    // }
    
}
