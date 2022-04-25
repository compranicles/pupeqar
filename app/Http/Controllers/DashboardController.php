<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\{
    College,
    Department,
    Quarter,
    Sector
};
use App\Models\Authentication\UserRole;
use App\Models\{
    Announcement,
    Chairperson,
    Dean,
    Employee,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
    Role,
    SectorHead,
    User,
};
use App\Services\{
    UserRoleService,
    DashboardService
};

class DashboardController extends Controller
{
    public function index() {
        $user = User::where('id', auth()->id())->first();
        // $employee = Employee::where('user_id', $user['id'])->first();
        $roles = (new UserRoleService())->getRolesOfUser(auth()->id());
        // if ($employee == null && (in_array(1, $roles) || in_array(3, $roles))) {
        //     request()->session()->flash('flash.banner', "Complete your account information. Click here.");
        // }
        $roleNames = Role::whereIn('id', $roles)->pluck('name')->all();
        $userRoleNames = '';
        foreach($roleNames as $roleName) {
            $userRoleNames = $userRoleNames.' '.$roleName;
        }
        $currentQuarterYear = Quarter::find(1);
        $countAccomplishmentsSubmitted = Report::where('user_id', auth()->id())
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
        $countAccomplishmentsReturned = Report::where('user_id', auth()->id())
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->where(function ($query) {
                        $query->where('researcher_approval', 0)
                            ->orWhere('extensionist_approval', 0)
                            ->orWhere('chairperson_approval', 0)
                            ->orWhere('dean_approval', 0)
                            ->orWhere('sector_approval', 0)
                            ->orWhere('ipqmso_approval', 0);
                    })
                    ->count();
        
        if (in_array(1, $roles) || in_array(3, $roles)) {
            $department = '';
            $college = '';
            $sector = '';
            $countFaculty = '';
            $countAdmin = '';
            $countChairperson = '';
            $countDirector = '';
            $countSectorHead = '';
            $arrayOfNoOfAllUsers = '';
            $countReviewed1 = '';
            $countReviewed2 = "";
        }
        
        if (in_array(10, $roles)) {
            $college = '';
            $sector = '';
            $arrayOfNoOfAllUsers = '';
            $department = Department::join('faculty_researchers', 'departments.id', 'faculty_researchers.department_id')->where('faculty_researchers.user_id', auth()->id())->get();
            $countReviewed1 = (new DashboardService())->countAccomplishmentByOfficerAndDepartmentAndStatusAndQuarterYearAndReportCategoryID('researcher_approval', $department[0]->department_id, 1, $currentQuarterYear, '1,2,3,4,5,6,7');
            $countReviewed2 = (new DashboardService())->countAccomplishmentByOfficerAndDepartmentAndStatusAndQuarterYearAndReportCategoryID('researcher_approval', $department[0]->department_id, 1, $currentQuarterYear, '8');
        }
        if (in_array(11, $roles)) {
            $college = '';
            $sector = '';
            $arrayOfNoOfAllUsers = '';
            $department = Department::join('faculty_extensionists', 'departments.id', 'faculty_extensionists.department_id')->where('faculty_extensionists.user_id', auth()->id())->get();
            $countReviewed1 = (new DashboardService())->countAccomplishmentByOfficerAndDepartmentAndStatusAndQuarterYearAndReportCategoryID('extensionist_approval', $department[0]->department_id, 1, $currentQuarterYear, '9,10,11,12,13,14');
            $countReviewed2 = "";
        }
        if (in_array(5, $roles)) {
            $college = '';
            $sector = '';
            $arrayOfNoOfAllUsers = '';
            $department = Department::join('chairpeople', 'departments.id', 'chairpeople.department_id')->where('chairpeople.user_id', auth()->id())->get();
            $countReviewed1 = (new DashboardService())->countAccomplishmentByOfficerAndDepartmentAndQuarterYear('chairperson_approval', $department[0]->department_id, $currentQuarterYear);
            $countReviewed2 = "";
        } 
        if (in_array(6, $roles)) {
            $department = '';
            $sector = '';
            $college = College::join('deans', 'colleges.id', 'deans.college_id')->where('deans.user_id', auth()->id())->get();
            $countFaculty = Employee::where('college_id', $college[0]->college_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 1)->count();
            $countAdmin = Employee::where('college_id', $college[0]->college_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 3)->count();
            $arrayOfNoOfAllUsers = array('faculty' => $countFaculty, 'admin' => $countAdmin);
            $countReviewed1 = (new DashboardService())->countAccomplishmentByOfficerAndCollegeAndQuarterYear('dean_approval', $college[0]->college_id, $currentQuarterYear);
            $countReviewed2 = "";
        } 
        if (in_array(7, $roles)) {
            $department = '';
            $sector = SectorHead::leftjoin('sectors', 'sector_heads.sector_id', 'sectors.id')->where('sector_heads.user_id', auth()->id())->first();
            $college = College::where('sector_id', $sector['sector_id'])->count();
            $countFaculty = Employee::where('sector_id', $sector['sector_id'])->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 1)->count();
            $countAdmin = Employee::where('sector_id', $sector['sector_id'])->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 3)->count();
            $arrayOfNoOfAllUsers = array('faculty' => $countFaculty, 'admin' => $countAdmin);
            $countReviewed1 = (new DashboardService())->countAccomplishmentByOfficerAndCollegeAndQuarterYear('sector_approval', $sector['sector_id'], $currentQuarterYear);
            $countReviewed2 = "";

        } 
        if (in_array(8, $roles) || in_array(9, $roles)) {
            $department = '';
            $college = '';
            $sector = '';
            $countFaculty = (new UserRoleService())->getNumberOfUserByRole(1);
            $countAdmin = (new UserRoleService())->getNumberOfUserByRole(3);
            $countChairperson = (new UserRoleService())->getNumberOfUserByRole(5);
            $countDirector = (new UserRoleService())->getNumberOfUserByRole(6);
            $countSectorHead = (new UserRoleService())->getNumberOfUserByRole(7);
            $countIPO = (new UserRoleService())->getNumberOfUserByRole(8);
            $arrayOfNoOfAllUsers = array('faculty' => $countFaculty, 'admin' => $countAdmin, 'chairperson' => $countChairperson, 
                    'director' => $countDirector, 'sectorHead' => $countSectorHead, 'ipo' => $countIPO);
            $countReviewed1 = '';
            $countReviewed2 = "";
        } 
        
        $announcements = Announcement::where('status', 1)->orderBy('updated_at')->paginate(3);
        return view('dashboard', compact('user', 'roles', 'userRoleNames', 'currentQuarterYear', 'announcements', 'countAccomplishmentsSubmitted',
                'countAccomplishmentsReturned', 'countReviewed1', 'countReviewed2', 'arrayOfNoOfAllUsers', 
                'department', 'college', 'sector'));
    }
}
