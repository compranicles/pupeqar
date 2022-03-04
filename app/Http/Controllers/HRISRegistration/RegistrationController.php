<?php

namespace App\Http\Controllers\HRISRegistration;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;

class RegistrationController extends Controller
{
    private $userData;

    public function index(){

        return view('hris-regi.check');
    }

    public function verify(Request $request){

        $db_ext = DB::connection('mysql_external');

        $user = $db_ext->update("SET NOCOUNT ON; EXEC ValidateLogIn N'$request->email',N'$request->password' ");

        $userLocal =  User::where('email', $request->email)->first();

        if ($user == '-1') {

            if (!empty($userLocal)){
                return redirect()->route('home')->with('error', 'User already registered. Log in to continue');
            }

            $user = $db_ext->select(" EXEC ValidateLogIn N'$request->email',N'$request->password' ");
            
            $key = Str::random(10);

            $request->session()->put($key, $user);

            return redirect()->route('register.create', $key);
        }
        
        return redirect()->route('register.hris')->with('error', 'Invalid username or password');
    }

    public function create(Request $request, $key){

        $user = $request->session()->get($key)[0];
        
        $request->session()->forget($key);
        
        return view('hris-regi.form', compact('user'));
    }

    public function save(Request $request){
        $user = User::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'emp_code' => $request->emp_code,
            'emp_id' => $request->emp_id,
        ]);
        
        UserRole::create(['user_id' => $user->id, 'role_id' => $request->role]);

        return redirect()->route('home')->with('success', 'Account successfully saved. Log In to continue');
    }
}
