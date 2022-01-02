<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Research;
use App\Models\Invention;
use App\Models\Syllabus;
use App\Models\Reference;
use App\Models\ExpertServiceConsultant;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceAcademic;
use App\Models\ExtensionService;
use App\Models\Partnership;
use App\Models\Mobility;
use App\Models\Request as RequestModel;
use App\Models\StudentAward;
use App\Models\StudentTraining;
use App\Models\ViableProject;
use App\Models\CollegeDepartmentAward;
use App\Models\TechnicalExtension;
use App\Models\OutreachProgram;

class DashboardController extends Controller
{
    public function index() {
        $research = Research::select()->where('research.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $inventions = Invention::select()->where('inventions.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $syllabus = Syllabus::select()->where('syllabi.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $references = Reference::select()->where('references.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esconsultant = ExpertServiceConsultant::select()->where('expert_service_consultants.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esconference = ExpertServiceConference::select()->where('expert_service_conferences.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esacademic = ExpertServiceAcademic::select()->where('expert_service_academics.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $es = ExtensionService::select()->where('extension_services.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $partnerships = Partnership::select()->where('partnerships.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $mobilities = Mobility::select()->where('mobilities.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $requests = RequestModel::select()->where('requests.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $student_awards = StudentAward::select()->where('student_awards.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $student_trainings = StudentTraining::select()->where('student_trainings.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $viable_projects = ViableProject::select()->where('viable_projects.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $cdawards = CollegeDepartmentAward::select()->where('college_department_awards.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $technical_extensions = TechnicalExtension::select()->where('technical_extensions.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $outreach_programs = OutreachProgram::select()->where('outreach_programs.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();

        $sum = $research + $inventions + $syllabus + $references + $esconsultant + 
                    $esconference + $esacademic + $es + $partnerships +$mobilities + $requests+
                    $student_awards + $student_trainings + $viable_projects + $cdawards +
                    $technical_extensions + $outreach_programs;


            return view('dashboard', compact('sum'));
    }
}