<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    User,
    Employee,
};
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
        $roles = implode(', ', $roles);
        $employeeSectorsCbcoDepartment = Employee::where('employees.user_id', $user->id)
                            ->join('colleges', 'employees.college_id', 'colleges.id')
                            ->select('employees.id', 'colleges.name as collegeName')
                            ->get();
        $employeeTypeOfUser = Employee::where('user_id', auth()->id())->groupBy('type')->oldest()->get();

        $designations = [];
        foreach($employeeTypeOfUser as $employee) {
            $designations[$employee->type] = Employee::where('user_id', auth()->id())
                ->where('employees.type', $employee->type)
                ->join('colleges', 'colleges.id', 'employees.college_id')
                ->pluck('colleges.name')
                ->all();
        }

        return view('account', compact('accountDetail', 'employeeDetail', 'roles', 'employeeSectorsCbcoDepartment', 'user',
            'employeeTypeOfUser', 'designations'));
    }
}
