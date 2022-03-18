<?php

namespace App\Http\Controllers;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Mobility;
use App\Models\Research;
use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\SectorHead;
use App\Models\Chairperson;
use App\Models\Partnership;
use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\ViableProject;
use App\Models\OutreachProgram;
use App\Models\StudentTraining;
use App\Models\ExtensionService;
use App\Models\TechnicalExtension;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Models\ExpertServiceAcademic;
use App\Models\CollegeDepartmentAward;
use App\Models\Authentication\UserRole;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use App\Models\FacultyResearcher;
use App\Models\FacultyExtensionist;
use App\Models\Request as RequestModel;
use App\Services\UserRoleService;

class DashboardController extends Controller
{
    public function index() {
        
        $roles = (new UserRoleService())->getRolesOfUser(auth()->id());
        
        $currentQuarterYear = Quarter::find(1);

        $countFaculty = (new UserRoleService())->getNumberOfUserByRole(1);
        $countAdmin = (new UserRoleService())->getNumberOfUserByRole(3);
        $countChairperson = (new UserRoleService())->getNumberOfUserByRole(5);
        $countDirector = (new UserRoleService())->getNumberOfUserByRole(6);
        $countSectorHead = (new UserRoleService())->getNumberOfUserByRole(7);
        $countIPOEmployee = (new UserRoleService())->getNumberOfUserByRole(8);
        $countResearcher = (new UserRoleService())->getNumberOfUserByRole(10);
        $countExtensionist = (new UserRoleService())->getNumberOfUserByRole(11);
        $arrayOfNoOfAllUsers = array('faculty' => $countFaculty, 'admin' => $countAdmin, 'chairperson' => $countChairperson, 
            'director' => $countDirector, 'sectorHead' => $countSectorHead,
            'ipo' => $countIPOEmployee, 'researcher' => $countResearcher, 'extensionist' => $countExtensionist);

        if (in_array(5, $roles)) {
            $chairpersonDepartment = Chairperson::where('user_id', auth()->id())
                                    ->join('departments', 'departments.id', 'chairpeople.department_id')
                                    ->select('departments.name')
                                    ->get();
        } else {
            $chairpersonDepartment = '';
        }

        if (in_array(6, $roles)) {
            $directorOffice = Dean::where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'deans.college_id')
                                    ->select('colleges.name')
                                    ->get();
        } else {
            $directorOffice = '';
        }

        if (in_array(7, $roles)) {
            $sector = SectorHead::where('user_id', auth()->id())
                                    ->join('sectors', 'sectors.id', 'sector_heads.sector_id')
                                    ->select('sectors.name')
                                    ->get();
        } else {
            $sector = '';
        }

        if (in_array(10, $roles)) {
            $researcherDepartment = FacultyResearcher::where('user_id', auth()->id())
                                ->join('departments', 'departments.id', 'faculty_researchers.department_id')
                                ->select('departments.name')
                                ->get();
            // dd($researcherDepartment);
        } else {
            $researcherDepartment = '';
        }

        if (in_array(11, $roles)) {
            $extensionistDepartment = FacultyExtensionist::where('user_id', auth()->id())
                                ->join('departments', 'departments.id', 'faculty_extensionists.department_id')
                                ->select('departments.name')
                                ->get();
        } else {
            $extensionistDepartment = '';
        }

        return view('dashboard', compact('roles', 'currentQuarterYear', 'arrayOfNoOfAllUsers', 'chairpersonDepartment', 'directorOffice',
                'sector', 'researcherDepartment', 'extensionistDepartment'));
    }
}
