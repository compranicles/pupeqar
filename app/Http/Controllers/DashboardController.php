<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    Associate,
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

        $roles = (new UserRoleService())->getRolesOfUser(auth()->id());

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
                    ->whereNotNull('college_id')
                    ->whereNotNull('department_id')
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
        $countToReview = [];
        $countRegisteredUsers = [];
        $countExpectedTotal = [];
        $countReceived = [];

        if (in_array(1, $roles)) {
            $department[1] = '';
            $college[1] = '';
            $sector[1] = '';
            $countToReview[1] = '';
            $countRegisteredUsers[1] = '';
            $countExpectedTotal[1] = '';
            $countReceived[1] = '';

        }

        if (in_array(3, $roles)) {
            $department[3] = '';
            $college[3] = '';
            $sector[3] = '';
            $countToReview[3] = '';
            $countRegisteredUsers[3] = '';
            $countExpectedTotal[3] = '';
            $countReceived[3] = '';

        }
        
        if (in_array(10, $roles)) {
            $college[10] = '';
            $sector[10] = '';
            $countRegisteredUsers[10] = '';
            $countExpectedTotal[10] = '';
            $countReceived[10] = '';

            $department[10] = College::join('faculty_researchers', 'colleges.id', 'faculty_researchers.college_id')
                    ->whereNull('faculty_researchers.deleted_at')
                    ->where('faculty_researchers.user_id', auth()->id())->get();
            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[10] as $value){
                $tempcount = Report::whereNull('researcher_approval')
                    ->where('college_id', $value->college_id)
                    ->whereIn('report_category_id', [1, 2, 3, 4, 5, 6, 7, 8])
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->college_id] = $tempcount;
            }
            $countToReview[10] = $tempvalues;
        }
        if (in_array(11, $roles)) {
            $college[11] = '';
            $sector[11] = '';
            $countRegisteredUsers[11] = '';
            $countExpectedTotal[11] = '';
            $countReceived[11] = '';

            $department[11] = College::join('faculty_extensionists', 'colleges.id', 'faculty_extensionists.college_id')
                    ->whereNull('faculty_extensionists.deleted_at')
                    ->where('faculty_extensionists.user_id', auth()->id())->get();

            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[11] as $value){
                $tempcount = Report::whereNull('extensionist_approval')
                    ->where('college_id', $value->college_id)
                    ->whereIn('report_category_id', [9, 10, 11, 12, 13, 14, 22, 23, 34, 35, 36, 37])
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->college_id] = $tempcount;
            }
            $countToReview[11] = $tempvalues;

        }
        if (in_array(5, $roles)) {
            $college[5] = '';
            $sector[5] = '';
            $countRegisteredUsers[5] = '';
            $countExpectedTotal[5] = '';
            $countReceived[5] = '';

            $department[5] = Department::join('chairpeople', 'departments.id', 'chairpeople.department_id')->where('chairpeople.user_id', auth()->id())->where('chairpeople.deleted_at', null)->get();
            
            $tempcount = 0;
            $tempvalues = [];
            foreach ($department[5] as $value){
                $tempcount = Report::whereNull('chairperson_approval')
                    ->where('department_id', $value->department_id)
                    ->whereIn('report_category_id', [15, 16, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 38])
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->department_id] = $tempcount;
                
            }
            $countToReview[5] = $tempvalues;

        } 
        if (in_array(6, $roles) || in_array(12, $roles)) {
            $department[6] = '';
            $sector[6] = '';
            $countRegisteredUsers[6] = '';
            $countExpectedTotal[6] = '';
            $countReceived[6] = '';

            if (in_array(12, $roles)) {
                $college[6] = College::join('associates', 'colleges.id', 'associates.college_id')->where('associates.user_id', auth()->id())->where('associates.deleted_at', null)->get();
            }

            if (in_array(6, $roles)) {
                $college[6] = College::join('deans', 'colleges.id', 'deans.college_id')->where('deans.user_id', auth()->id())->where('deans.deleted_at', null)->get();
            }
            
            $tempcount = 0;
            $tempvalues = [];
            foreach ($college[6] as $value){
                $tempcount = Report::whereNull('dean_approval')
                    ->where('chairperson_approval', 1)
                    ->where('college_id', $value->college_id)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->college_id] = $tempcount;
                
            }
            $countToReview[6] = $tempvalues;
        } 
        if (in_array(7, $roles) || in_array(13, $roles)) {
            $department[7] = '';
            $countRegisteredUsers[7] = '';
            $countExpectedTotal[7] = '';
            $countReceived[7] = '';

            if (in_array(13, $roles)) {
                $sector[7] = Associate::leftjoin('sectors', 'associates.sector_id', 'sectors.id')->where('associates.user_id', auth()->id())->where('associates.deleted_at', null)->get();
            }

            if (in_array(7, $roles)) {
                $sector[7] = SectorHead::leftjoin('sectors', 'sector_heads.sector_id', 'sectors.id')->where('sector_heads.user_id', auth()->id())->where('sector_heads.deleted_at', null)->get();
            }
            
            foreach ($sector[7] as $value){
                $tempcount = Report::whereNull('sector_approval')
                    ->whereIn('dean_approval', [1,2])
                    ->where('sector_id', $value->sector_id)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->count();
                $tempvalues[$value->sector_id] = $tempcount; 
                

            }
            $countToReview[7] = $tempvalues;
        } 
        if (in_array(8, $roles)) {
            $department[8] = '';
            $college[8] = '';
            $sector[8] = '';
            $countRegisteredUsers[8] = '';
            $countReceived[8] = Report::where('ipqmso_approval', 1)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $countExpectedTotal[8] = Report::where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $countToReview[8] = Report::whereNull('ipqmso_approval')
                ->whereIn('sector_approval', [1,2])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        } 
        if (in_array(9, $roles)) {
            $department[9] = '';
            $college[9] = '';
            $sector[9] = '';
            $countToReview[9] = '';
            $countExpectedTotal[9] = '';
            $countReceived[9] = '';
            $countRegisteredUsers[9] = User::count();
        } 

        $announcements = Announcement::where('status', 1)->orderBy('updated_at', 'DESC')->paginate(5);
        return view('dashboard', compact('user', 'roles', 'userRoleNames', 'currentQuarterYear', 'announcements', 'countAccomplishmentsSubmitted',
                'countAccomplishmentsReturned', 'countToReview', 'department', 'college', 'sector', 
                'countRegisteredUsers', 'countExpectedTotal', 'countReceived'));
    }
}
