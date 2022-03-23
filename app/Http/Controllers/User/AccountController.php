<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee;
use App\Models\Authentication\UserRole;
use App\Models\Maintenance\{
    Sector,
    College,
    Department
};

class AccountController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->id());
        
        $db_ext = DB::connection('mysql_external');

        $employeeDetail = $db_ext->select("EXEC GetEmployeeByEmpCode N'$user->emp_code'");
        $accountDetail = $db_ext->select("EXEC GetUserAccount N'$user->user_account_id'");
        $roles = UserRole::where('user_id', $user->id)->join('roles', 'roles.id', 'user_roles.role_id')
                            ->pluck('roles.name')
                            ->all();
        $employeeSectorsCbcoDepartment = Employee::where('employees.user_id', $user->id)
                                ->join('sectors', 'employees.sector_id', 'sectors.id')
                                ->join('colleges', 'employees.college_id', 'colleges.id')
                                ->select('employees.id', 'sectors.name as sectorName', 'colleges.name as collegeName')
                                ->get();

        return view('account', compact('accountDetail', 'employeeDetail', 'roles', 'employeeSectorsCbcoDepartment', 'user'));
    }
}
