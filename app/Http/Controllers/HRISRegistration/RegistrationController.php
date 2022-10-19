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

        if ($user == '-1') {
            
            if (!empty($userLocal)){
                $isAdmin = UserRole::where('user_id', $userLocal->id)->whereIn('role_id', [9])->exists();
                if(!$this->scheduleCheck($userLocal->id) && !$isAdmin){
                    return redirect()->back()->with('error', 'The college you are in is not scheduled to login today');
                }
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

    public function scheduleCheck($userId) {
        // Sched name, start_date, end_date,start_time,end_time
        /*$loginSchedulesTable = array(
            array(
                "schedule_id" => 1,
                "schedule_name" => "Oct 19 sched1",
                "start_date" => "2022-10-19",
                "end_date" => "2022-10-19",
                "start_time" => "07:00 AM",
                "end_time" => "01:00 PM"
            ),
            array(
                "schedule_id" => 2,
                "schedule_name" => "Oct 19 sched2",
                "start_date" => "2022-10-19",
                "end_date" => "2022-10-19",
                "start_time" => "01:00 PM",
                "end_time" => "07:00 PM"
            ),
            array(
                "schedule_id" => 3,
                "schedule_name" => "Oct 20 sched1",
                "start_date" => "2022-10-20",
                "end_date" => "2022-10-20",
                "start_time" => "07:00 AM",
                "end_time" => "01:00 PM"
            ),
            array(
                "schedule_id" => 4,
                "schedule_name" => "Oct 20 sched2",
                "start_date" => "2022-10-20",
                "end_date" => "2022-10-20",
                "start_time" => "01:00 PM",
                "end_time" => "07:00 PM"
            )
        );
        //sector_id, college_id, login_schedule_id
        $scheduledLoginsTable = array(
            array("sector_id" => null, "college_id" => 61, "role_id" => null, "login_schedule_id" => 1),
            array("sector_id" => null, "college_id" => 66, "role_id" => null, "login_schedule_id" => 1),
            array("sector_id" => null, "college_id" => 8, "role_id" => null, "login_schedule_id" => 1),
            array("sector_id" => null, "college_id" => 159, "role_id" => null, "login_schedule_id" => 1),
            array("sector_id" => null, "college_id" => 137, "role_id" => null, "login_schedule_id" => 1),
            array("sector_id" => null, "college_id" => 176, "role_id" => null, "login_schedule_id" => 2),
            array("sector_id" => null, "college_id" => 137, "role_id" => null, "login_schedule_id" => 2),
            array("sector_id" => null, "college_id" => 211, "role_id" => null, "login_schedule_id" => 2),
            array("sector_id" => null, "college_id" => 200, "role_id" => null, "login_schedule_id" => 2),

            array("sector_id" => null, "college_id" => 238, "role_id" => null, "login_schedule_id" => 3),
            array("sector_id" => null, "college_id" => 243, "role_id" => null, "login_schedule_id" => 3),
            array("sector_id" => null, "college_id" => 233, "role_id" => null, "login_schedule_id" => 3),
            array("sector_id" => null, "college_id" => 239, "role_id" => null, "login_schedule_id" => 3),
            array("sector_id" => null, "college_id" => 25, "role_id" => null, "login_schedule_id" => 4),
            array("sector_id" => null, "college_id" => 36, "role_id" => null, "login_schedule_id" => 4),
            array("sector_id" => null, "college_id" => 40, "role_id" => null, "login_schedule_id" => 4),
            array("sector_id" => null, "college_id" => 274, "role_id" => null, "login_schedule_id" => 4),
        );*/
        // query this userid, roleid, sectorid, collegeid, department, office
        $schedSummary = array(
            array("role","2022-10-18","04:00 PM","11:59 PM",array(5,6)),
            array("role","2022-10-19","12:00 AM","06:00 AM",array(5,6)),
            array("college","2022-10-18","04:00 PM","11:59 PM",array(94,19,137)),
            array("college","2022-10-19","12:00 AM","06:00 AM",array(94,19,137)),
            array("college","2022-10-19","07:00 AM","01:00 PM",array(61,66,8,159,137)),
            array("college","2022-10-19","01:00 PM","07:00 PM",array(176,137,211,120,27,226,59,94,75)),
            array("college","2022-10-20","07:00 AM","01:00 PM",array(238,243,233,239)),
            array("college","2022-10-20","01:00 PM","07:00 PM",array(25,36,40,274))
        );

        $dateToday = Carbon::today()->toDateString();
        $selectedScheds = array();
        foreach ($schedSummary as $key => $value) {
            $schedDate = $value[1];
            $schedStart = $value[2];
            $schedStop = $value[3];
            if(($dateToday == $schedDate) && (Carbon::now()->between($schedStart,$schedStop,true))){
                array_push($selectedScheds, $key);
            }
        }
        if (count($selectedScheds) == 0) {
            return true;
        }
        error_log('Some message here.');
        foreach ($selectedScheds as $sched) {
            if ($schedSummary[$sched][0]=="role" && UserRole::where('user_id', $userId)->whereIn('role_id',$schedSummary[$sched][4])->exists()){
                error_log('Some message here. ROLE');
                return true;
            } elseif ($schedSummary[$sched][0]=="college" && Employee::where('user_id', $userId)->whereIn('college_id',$schedSummary[$sched][4])->exists()){
                error_log('Some message here. COLLEGE');
                return true;
            }
        }

        return false;




        // $allowedColleges = array();
        // $dateRange = ['2022-10-11','2022-10-12','2022-10-13','2022-10-14'];
        // $dateToday = Carbon::today()->toDateString();
        
        // switch ($dateToday) {
        //     /*case '2022-10-06':
        //         array_push($allowedColleges,239,233,238,243);
        //         array_push($allowedColleges,176,159,137,211,200);
        //         break;*/
        
        //     case in_array($dateToday,$dateRange):
        //         /*array_push($allowedColleges,120,226,94);
        //         array_push($allowedColleges,59,75,18,2);*/
        //         array_push($allowedColleges,36);
        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }
        
        // $startTime = Carbon::createFromFormat('H:i a', '08:00 AM');
        // $endTime = Carbon::createFromFormat('H:i a', '05:00 PM');
        // $timeCheck = Carbon::now()->between($startTime,$endTime,true);

        // if(Employee::where('user_id', $userId)->whereIn('college_id',$allowedColleges)->doesntExist() && $timeCheck && sizeof($allowedColleges)>0){
        //     return false;
        // } else {
        //     return true;
        // }
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
            if($this->scheduleCheck($userLocal->id)){
                return redirect()->back()->with('error', 'The college you are in is not scheduled to login today');
            }
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
