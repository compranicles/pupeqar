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
        $i = 0;
        foreach($roleNames as $roleName) {
            if ($i == 0) {
                $userRoleNames = $userRoleNames.' '.$roleName;
            } else {
                $userRoleNames = $userRoleNames.' · '.$roleName;
            }
            $i++;
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
        
        $department = [];
        $college = [];
        $sector = [];
        $countFaculty = [];
        $countAdmin = [];
        $countChairperson = [];
        $countDirector = [];
        $countSectorHead = [];
        $arrayOfNoOfAllUsers = [];
        $countReviewed1 = [];
        $countReviewed2 = [];


        if (in_array(1, $roles)) {
            $department[1] = '';
            $college[1] = '';
            $sector[1] = '';
            $countFaculty[1] = '';
            $countAdmin[1] = '';
            $countChairperson[1] = '';
            $countDirector[1] = '';
            $countSectorHead[1] = '';
            $arrayOfNoOfAllUsers[1] = '';
            $countReviewed1[1] = '';
            $countReviewed2[1] = "";
        }

        if (in_array(3, $roles)) {
            $department[3] = '';
            $college[3] = '';
            $sector[3] = '';
            $countFaculty[3] = '';
            $countAdmin[3] = '';
            $countChairperson[3] = '';
            $countDirector[3] = '';
            $countSectorHead[3] = '';
            $arrayOfNoOfAllUsers[3] = '';
            $countReviewed1[3] = '';
            $countReviewed2[3] = "";
        }
        
        if (in_array(10, $roles)) {
            $college[10] = '';
            $sector[10] = '';
            $arrayOfNoOfAllUsers[10] = '';
            $department[10] = Department::join('faculty_researchers', 'departments.id', 'faculty_researchers.department_id')
                    ->whereNull('faculty_researchers.deleted_at')
                    ->where('faculty_researchers.user_id', auth()->id())->get();
            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[10] as $value){
                $tempcount = Report::where('researcher_approval', 1)
                    ->where('department_id', $value->department_id)
                    ->whereIn('report_category_id', [1, 2, 3, 4, 5, 6, 7])
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed1[10] = $tempvalues;

            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[10] as $value){
                $tempcount = Report::where('researcher_approval', 1)
                    ->where('department_id', $value->department_id)
                    ->where('report_category_id', 8)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed2[10] = $tempvalues;
        }
        if (in_array(11, $roles)) {
            $college[11] = '';
            $sector[11] = '';
            $arrayOfNoOfAllUsers[11] = '';
            $department[11] = Department::join('faculty_extensionists', 'departments.id', 'faculty_extensionists.department_id')
                    ->whereNull('faculty_extensionists.deleted_at')
                    ->where('faculty_extensionists.user_id', auth()->id())->get();

            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[11] as $value){
                $tempcount = Report::where('extensionist_approval', 1)
                    ->where('department_id', $value->department_id)
                    ->whereIn('report_category_id', [9, 10, 11, 12, 13, 14])
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed1[11] = $tempvalues;

            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[11] as $value){
                $tempcount = '';
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed2[11] = $tempvalues;
        }
        if (in_array(5, $roles)) {
            $college[5] = '';
            $sector[5] = '';
            $arrayOfNoOfAllUsers[5] = '';
            $department[5] = Department::join('chairpeople', 'departments.id', 'chairpeople.department_id')->where('chairpeople.user_id', auth()->id())->where('chairpeople.deleted_at', null)->get();
            
            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[5] as $value){
                $tempcount = (new DashboardService())->countAccomplishmentByOfficerAndDepartmentAndQuarterYear('chairperson_approval', $value->department_id, $currentQuarterYear);
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed1[5] = $tempvalues;

            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[5] as $value){
                $tempcount = '';
                $tempvalues[$value->department_id] = $tempcount;
            }
            $countReviewed2[5] = $tempvalues;
        } 
        if (in_array(6, $roles)) {
            $department[6] = '';
            $sector[6] = '';
            $college[6] = College::join('deans', 'colleges.id', 'deans.college_id')->where('deans.user_id', auth()->id())->where('deans.deleted_at', null)->get();
            
            $arrayOfNoOfAllUsers[6] = [];
            $tempcount1 = 0;
            $tempcount2 = 0;

            foreach ($college[6] as $value) {
                $tempcount1 = Employee::where('college_id', $value->college_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 1)->count();
                $tempcount2 = Employee::where('college_id', $value->college_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 3)->count();
                $temp = array('faculty' => $tempcount1, 'admin' => $tempcount2);
                $arrayOfNoOfAllUsers[6] = [$value->college_id => $temp];
            }
            
            $tempcount = 0;
            $tempvalues = [];
            foreach ($college[6] as $value){
                $tempcount = (new DashboardService())->countAccomplishmentByOfficerAndCollegeAndQuarterYear('dean_approval', $value->college_id, $currentQuarterYear);
                $tempvalues[$value->college_id] = $tempcount;
            }
            $countReviewed1[6] = $tempvalues;

            $tempcount = 0;
            $tempvalues = [];
            foreach ($college[6] as $value){
                $tempcount = '';
                $tempvalues[$value->college_id] = $tempcount;
            }
            $countReviewed2[6] = $tempvalues;
        } 
        if (in_array(7, $roles)) {
            $department[7] = '';
            $sector[7] = SectorHead::leftjoin('sectors', 'sector_heads.sector_id', 'sectors.id')->where('sector_heads.user_id', auth()->id())->where('sector_heads.deleted_at', null)->first();
            $college[7] = College::where('sector_id', $sector[7]->sector_id)->count();
            $countFaculty = Employee::where('sector_id', $sector[7]->sector_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 1)->count();
            $countAdmin = Employee::where('sector_id', $sector[7]->sector_id)->join('user_roles', 'user_roles.user_id', 'employees.user_id')->where('user_roles.role_id', 3)->count();
            $arrayOfNoOfAllUsers[7] = array('faculty' => $countFaculty, 'admin' => $countAdmin);
            $countReviewed1[7] = (new DashboardService())->countAccomplishmentByOfficerAndCollegeAndQuarterYear('sector_approval', $sector[7]->sector_id, $currentQuarterYear);
            $countReviewed2[7] = "";
        } 
        if (in_array(8, $roles) || in_array(9, $roles)) {
            $department[8] = '';
            $college[8] = '';
            $sector[8] = '';
            $countFaculty = (new UserRoleService())->getNumberOfUserByRole(1);
            $countAdmin = (new UserRoleService())->getNumberOfUserByRole(3);
            $countChairperson = (new UserRoleService())->getNumberOfUserByRole(5);
            $countDirector = (new UserRoleService())->getNumberOfUserByRole(6);
            $countSectorHead = (new UserRoleService())->getNumberOfUserByRole(7);
            $countIPO = (new UserRoleService())->getNumberOfUserByRole(8);
            $arrayOfNoOfAllUsers[8] = array('faculty' => $countFaculty, 'admin' => $countAdmin, 'chairperson' => $countChairperson, 
                    'director' => $countDirector, 'sectorHead' => $countSectorHead, 'ipo' => $countIPO);
            $countReviewed1[8] = '';
            $countReviewed2[8] = "";
        } 

        $announcements = Announcement::where('status', 1)->orderBy('updated_at')->paginate(3);
        return view('dashboard', compact('user', 'roles', 'userRoleNames', 'currentQuarterYear', 'announcements', 'countAccomplishmentsSubmitted',
                'countAccomplishmentsReturned', 'countReviewed1', 'countReviewed2', 'arrayOfNoOfAllUsers', 
                'department', 'college', 'sector'));
    }
}
