<?php

namespace App\Http\Controllers\HRISRegistration;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Authentication\UserRole;


class RegistrationController extends Controller
{
    private $userData;

    public function __construct(){
        $this->db_ext = DB::connection('mysql_external');
    }

    public function index(){

        return view('hris-regi.check');
    }

    public function verify(Request $request){

        $user = $this->db_ext->update("SET NOCOUNT ON; EXEC ValidateLogIn N'$request->email',N'$request->password' ");

        $userLocal =  User::where('email', $request->email)->first();

        if ($user == '-1') {

            if (!empty($userLocal)){
                Auth::login($userLocal);
                $user_role = UserRole::where('user_id', $userLocal->id)->whereIn('role_id', [1,3])->first();
                session(['user_type' => Role::where('id', $user_role->role_id)->first()->name]);
                if(Employee::where('user_id', $userLocal->id)->exists()){
                    return redirect()->route('home');
                }
                return redirect()->route('account')->with('incomplete_account', 'incomplete_account');
            }

            $user = $this->db_ext->select(" EXEC ValidateLogIn N'$request->email',N'$request->password' ");

            if($this->save($user)){
                $userLocal = User::where('email', $request->email)->first();
                Auth::login($userLocal);
                $user_role = UserRole::where('user_id', $userLocal->id)->whereIn('role_id', [1,3])->first();
                session(['user_type' => Role::where('id', $user_role->role_id)->first()->name]);
                if(Employee::where('user_id', $userLocal->id)->exists()){
                    return redirect()->route('home');
                }
                return redirect()->route('account')->with('incomplete_account', 'incomplete_account');
            }
        }

        return redirect()->back()->with('error', 'Invalid username or password');
    }

    public function create(Request $request, $key){

        $user = $request->session()->get($key)[0];

        $request->session()->forget($key);

        return view('hris-regi.form', compact('user'));
    }

    public function save($user){


        $user = User::create([
            'email' => $user[0]->UserName,
            'first_name' => $user[0]->FName,
            'middle_name' => $user[0]->MName,
            'last_name' => $user[0]->LName,
            'emp_code' => $user[0]->EmpCode,
            'emp_id' => $user[0]->EmpNo,
            'user_account_id' => $user[0]->UserAccountID
        ]);

        $currentPos = $this->db_ext->select(" EXEC GetEmployeeCurrentPositionByEmpCode N'$user->emp_code' ");
        if(empty($currentPos)){
            return false;
        }

        $roleID = 1;

        //if admin
        if($currentPos[0]->EmployeeTypeID == '1')
            $roleID = 3;

        UserRole::create(['user_id' => $user->id, 'role_id' => $roleID]);

        return true;
    }

    public function alternate(){
        return view('hris-regi.alternate');
    }

    public function alternateLog(Request $request){
        $userLocal = User::where('user_account_id', $request->email)->first();

        if($userLocal == null){
            $user = $this->db_ext->select(" EXEC GetUserAccount '$request->email' ");

            if($this->save($user)){
                $userLocal = User::where('user_account_id', $request->email)->first();
                Auth::login($userLocal);
                $user_role = UserRole::where('user_id', $userLocal->id)->whereIn('role_id', [1,3])->first();
                session(['user_type' => Role::where('id', $user_role->role_id)->first()->name]);
                if(Employee::where('user_id', $userLocal->id)->exists()){
                    return redirect()->route('home');
                }
                return redirect()->route('account')->with('incomplete_account', 'incomplete_account');
            }
        }
        else{
            Auth::login($userLocal);
            $user_role = UserRole::where('user_id', $userLocal->id)->whereIn('role_id', [1,3])->first();
            session(['user_type' => Role::where('id', $user_role->role_id)->first()->name]);
            if(Employee::where('user_id', $userLocal->id)->exists()){
                return redirect()->route('home');
            }
            return redirect()->route('account')->with('incomplete_account', 'incomplete_account');
        }
        dd($userLocal);
    }

    public function switch_type(){
        $pastvalue = session()->get('user_type');
        $newvalue = '';
        if($pastvalue == 'Faculty Employee'){
            session()->put('user_type', 'Admin Employee');
            $newvalue = 'Admin Employee';
        }
        else{
            session()->put('user_type', 'Faculty Employee');
            $newvalue = 'Faculty Employee';
        }
        return redirect()->back()->with('success_switch', 'Successfully switched to '.$newvalue.'!');
    }
}
