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
use Carbon\Carbon;


class RegistrationController extends Controller
{
    private $userData;

    public function __construct(){
        $this->db_ext = DB::connection('mysql_external');
    }

    public function index(){

        return view('hris-regi.check');
    }

    // CollegeId 211 159 137 for october 4
    public function verify(Request $request){
        
        $user = $this->db_ext->update("SET NOCOUNT ON; EXEC ValidateLogIn N'$request->email',N'$request->password' ");
        
        $userLocal =  User::where('email', $request->email)->first();

        $allowedColleges = array();
        $dateRange = ['2022-10-11','2022-10-12','2022-10-13','2022-10-14'];
        $dateToday = Carbon::today()->toDateString();
        
        switch ($dateToday) {
            case '2022-10-05':
                array_push($allowedColleges,200,19,137);
                break;

            case '2022-10-06':
                array_push($allowedColleges,239,233,238,243);
                break;

            case '2022-10-07':
                array_push($allowedColleges,274,42,40,36);
                array_push($allowedColleges,61,66,76,83,12,25,8);
                break;

            case '2022-10-10':
                array_push($allowedColleges,290,263,96,107,452,112);
                array_push($allowedColleges,99,103,115,451,114);
                break;

            case in_array($dateToday,$dateRange):
                array_push($allowedColleges,120,226,94);
                array_push($allowedColleges,59,75,18,2);
                break;
            
            default:
                # code...
                break;
        }
        
        $startTime = Carbon::createFromFormat('H:i a', '08:00 AM');
        $endTime = Carbon::createFromFormat('H:i a', '05:00 PM');
        $timeCheck = Carbon::now()->between($startTime,$endTime,true);

        if(Employee::where('user_id', $userLocal?->id)->whereIn('college_id',$allowedColleges)->doesntExist() && $timeCheck && sizeof($allowedColleges)>0){
            return redirect()->back()->with('error', 'The college you are in is not scheduled to login today');
        }

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

        $allowedColleges = array();
        $dateRange = ['2022-10-11','2022-10-12','2022-10-13','2022-10-14'];
        $dateToday = Carbon::today()->toDateString();
        
        switch ($dateToday) {
            case '2022-10-06':
                array_push($allowedColleges,239,233,238,243);
                array_push($allowedColleges,176,159,137,211,200);
                break;

            case '2022-10-07':
                array_push($allowedColleges,274,42,40,36);
                array_push($allowedColleges,61,66,76,83,12,25,8);
                break;

            case '2022-10-10':
                array_push($allowedColleges,290,263,96,107,452,112);
                array_push($allowedColleges,99,103,115,451,114);
                break;

            case in_array($dateToday,$dateRange):
                array_push($allowedColleges,120,226,94);
                array_push($allowedColleges,59,75,18,2);
                break;
            
            default:
                # code...
                break;
        }
        
        $startTime = Carbon::createFromFormat('H:i a', '08:00 AM');
        $endTime = Carbon::createFromFormat('H:i a', '05:00 PM');
        $timeCheck = Carbon::now()->between($startTime,$endTime,true);

        if(Employee::where('user_id', $userLocal->id)->whereIn('college_id',$allowedColleges)->doesntExist() && $timeCheck && sizeof($allowedColleges)>0){
            return redirect()->back()->with('error', 'The college you are in is not scheduled to login today');
        }

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
        return redirect()->route('dashboard')->with('success_switch', 'Successfully switched the individual reporting as '.$newvalue.'!');
    }
}
