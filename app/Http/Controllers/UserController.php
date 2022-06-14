<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Hash,
    Notification,
    Storage,
    URL,
};
use App\Models\{
    Chairperson,
    Dean,
    FacultyExtensionist,
    FacultyResearcher,
    Invite,
    Role,
    SectorHead,
    TemporaryFile,
    User,
    Authentication\Permission,
    Authentication\RolePermission,
    Authentication\UserRole,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Sector,
};
use App\Notifications\InviteNotification;
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

        $users = User::orderBy('users.updated_at', 'DESC')->get();

        $rolesperuser = [];

        foreach($users as $user){
            $rolesperuser[$user->id] = UserRole::where('user_roles.user_id',$user->id)
                    ->join('roles', 'roles.id', 'user_roles.role_id')
                    ->select('roles.name')
                    ->get();
        }

        return view('users.index', compact('users', 'rolesperuser'));
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
        
        foreach($roles as $role){
            if ($role == 5) {
                Chairperson::create([
                    'user_id' => $user_id,
                    'department_id' => $request->input('department') ?? null
                ]);
            }
            if ($role == 6) {
                Dean::create([
                    'user_id' => $user_id,
                    'college_id' => $request->input('college') ?? null
                ]);
            }
            if ($role == 7) {
                SectorHead::create([
                    'user_id' => $user_id,
                    'sector_id' => $request->input('sector') ?? null
                ]);
            }
            if ($role == 10) {
                FacultyResearcher::create([
                    'user_id' => $user_id,
                    'college_id' => $request->input('research') ?? null
                ]);
            }
            if ($role == 11) {
                FacultyExtensionist::create([
                    'user_id' => $user_id,
                    'college_id' => $request->input('extension') ?? null
                ]);
            }
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

        $roles = UserRole::select('roles.name')->join('roles', 'roles.id', 'user_roles.role_id')->where('user_roles.user_id',$user->id)->get();
        return view('users.show', compact('user', 'roles'));
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

        // dd($user);
        // $rolehaspermissions = RolePermission::join('user_roles', 'user_roles.role_id', '=', 'role_permissions.role_id')
        //                                 ->where('user_roles.user_id', '=', $user->id)
        //                                 ->select('role_permissions.role_id')
        //                                 ->get();
        $roles = Role::get();
        $yourroles = $user->userrole()->pluck('role_id')->all();
        $permissions = Permission::get();


        $departments = Department::select('name as text', 'id as value')->get();
        $colleges = College::select('name as text', 'id as value')->get();
        $sectors = Sector::select('name as text', 'id as value')->get();

        $chairperson = Chairperson::join('departments', 'departments.id', 'chairpeople.department_id')->where('user_id', $user->id)->pluck('departments.id')->all();
        $dean = Dean::join('colleges', 'colleges.id', 'deans.college_id')->where('user_id', $user->id)->pluck('colleges.id')->all();
        $sectorhead = SectorHead::join('sectors', 'sectors.id', 'sector_heads.sector_id')->where('user_id', $user->id)->pluck('sectors.id')->all();
        $researcher = FacultyResearcher::join('colleges', 'colleges.id', 'faculty_researchers.college_id')->where('user_id', $user->id)->pluck('colleges.id')->all();
        $extensionist = FacultyExtensionist::join('colleges', 'colleges.id', 'faculty_extensionists.college_id')->where('user_id', $user->id)->pluck('colleges.id')->all();

        return view('users.edit', compact('user', 'roles', 'permissions', 'yourroles', 'departments', 'chairperson', 'colleges', 'dean', 'sectors', 'sectorhead', 'researcher', 'extensionist'));
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

        $request->validate([
            'roles' => 'required'
        ]);
        $checkedroles = $request->input('roles');

        $allroles = $user->userrole()->pluck('role_id')->all();
        $trashedroles = $user->userrole()->onlyTrashed()->pluck('role_id')->all();

        if ($checkedroles == null) {
            $user->userrole()->delete();
            Chairperson::where('user_id', $user->id)->delete();
            Dean::where('user_id', $user->id)->delete();
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

        if(!in_array(5, $checkedroles)){
            Chairperson::where('user_id', $user->id)->delete();
        }
        else{
            Chairperson::where('user_id', $user->id)->delete();
            foreach($request->input('department') as $department){
                Chairperson::create([
                    'user_id' => $user->id,
                    'department_id' => $department
                ]);
            }
        }
        if(!in_array(6, $checkedroles)){
            Dean::where('user_id', $user->id)->delete();
        }
        else{
            Dean::where('user_id', $user->id)->delete();
            foreach($request->input('college') as $college){
                Dean::updateOrCreate([ 
                    'user_id' => $user->id, 
                    'college_id' => $college, 
                ]);
            }
        }
        if(!in_array(7, $checkedroles)){
            SectorHead::where('user_id', $user->id)->delete();
        }
        else{
            SectorHead::where('user_id', $user->id)->delete();
            foreach($request->input('sector') as $sector){
                SectorHead::updateOrCreate([ 
                    'user_id' => $user->id, 
                    'sector_id' => $sector, 
                ]);
            }
        }
        if(!in_array(10, $checkedroles)){
            FacultyResearcher::where('user_id', $user->id)->delete();
        }
        else{
            FacultyResearcher::where('user_id', $user->id)->delete();
            foreach($request->input('research') as $researchDepartment){
                FacultyResearcher::updateOrCreate([ 
                    'user_id' => $user->id, 
                    'college_id' => $researchDepartment, 
                ]);
            }
        }
        if(!in_array(11, $checkedroles)){
            FacultyExtensionist::where('user_id', $user->id)->delete();
        }
        else{
            FacultyExtensionist::where('user_id', $user->id)->delete();
            foreach($request->input('extension') as $extensionDepartment){
                FacultyExtensionist::updateOrCreate([ 
                    'user_id' => $user->id, 
                    'college_id' => $extensionDepartment, 
                ]);
            }
        }
        
        
        


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

    public function storeSignature(Request $request) {
        $user = User::where('id', auth()->id())->first();

            if($request->has('document')){
            
                $documents = $request->input('document');
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    // dd($temporaryFile);
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = $user->first_name.'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();
    
                        User::where('id', $user->id)->update([
                            'signature' => $fileName,
                        ]);
                    }
                }
            }
        \LogActivity::addToLog('Had uploaded a digital signature.');
        return redirect()->route('account')->with('success', 'Personal signature has been added in your account.');
    }
    
}
