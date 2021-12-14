<?php

namespace App\Http\Controllers\Submissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Research;
use App\Models\Invention;
use App\Models\Syllabus;
use App\Models\Reference;
use App\Models\StudentAward;
use App\Models\StudentTraining;
use App\Models\ViableProject;
use App\Models\CollegeDepartmentAward;
use App\Models\TechnicalExtension;
use App\Models\ExpertServiceConsultant;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceAcademic;
use App\Models\ExtensionService;
use App\Models\Partnership;
use App\Models\Mobility;
use App\Models\OutreachProgram;

class SubmissionController extends Controller
{
    public function incomplete() {
        $researches = Research::leftJoin('research_documents', 'research_documents.research_code', 'research.research_code')
                                        ->where('research_documents.research_code', null)
                                        ->where('user_id', auth()->id())->where('is_active_member', 1)
                                        ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                                        ->select('research.*', 'dropdown_options.name as status_name')
                                        ->orderBy('research.updated_at', 'desc')
                                        ->get();

                                      
        $inventions = Invention::leftJoin('invention_documents', 'invention_documents.invention_id', 'inventions.id')
                                        ->where('invention_documents.invention_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'inventions.status')
                                        ->select('inventions.*', 'dropdown_options.name as status_name')->orderBy('inventions.updated_at', 'desc')->get();

        $syllabi = Syllabus::leftJoin('syllabus_documents', 'syllabus_documents.syllabus_id', 'syllabi.id')
                                        ->where('syllabus_documents.syllabus_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                                        ->select('syllabi.*', 'dropdown_options.name as assigned_task_name')
                                        ->get();

        $allRtmmi = Reference::leftJoin('reference_documents', 'reference_documents.reference_id', 'references.id')
                                        ->where('reference_documents.reference_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                        ->select('references.*', 'dropdown_options.name as category_name')
                                        ->get();

        $student_awards = StudentAward::leftJoin('student_award_documents', 'student_award_documents.student_award_id', 'student_awards.id')
                                        ->where('student_award_documents.student_award_id', null)
                                        ->where('user_id', auth()->id())
                                        ->get();

        $student_trainings = StudentTraining::leftJoin('student_training_documents', 'student_training_documents.student_training_id', 'student_trainings.id')
                                        ->where('student_training_documents.student_training_id', null)
                                        ->where('user_id', auth()->id())->get();
        
        $viable_projects = ViableProject::leftJoin('viable_project_documents', 'viable_project_documents.viable_project_id', 'viable_projects.id')
                                        ->where('viable_project_documents.viable_project_id', null)
                                        ->where('user_id', auth()->id())->get();

        $college_department_awards = CollegeDepartmentAward::leftJoin('college_department_award_documents', 'college_department_award_documents.college_department_award_id', 'college_department_awards.id')
                                        ->where('college_department_award_documents.college_department_award_id', null)
                                        ->where('user_id', auth()->id())->get();

        $technical_extensions = TechnicalExtension::leftJoin('technical_extension_documents', 'technical_extension_documents.technical_extension_id', 'technical_extensions.id')
                                        ->where('technical_extension_documents.technical_extension_id', null)
                                        ->where('user_id', auth()->id())->get();

        $expertServicesConsultant = ExpertServiceConsultant::leftJoin('expert_service_consultant_documents', 'expert_service_consultant_documents.expert_service_consultant_id', 'expert_service_consultants.id')
                                        ->where('expert_service_consultant_documents.expert_service_consultant_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_consultants.classification')
                                        ->select('expert_service_consultants.*', 'dropdown_options.name as classification_name')
                                        ->get(); 

        $expertServicesConference = ExpertServiceConference::leftJoin('expert_service_conference_documents', 'expert_service_conference_documents.expert_service_conference_id', 'expert_service_conferences.id')
                                        ->where('expert_service_conference_documents.expert_service_conference_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                        ->select('expert_service_conferences.*', 'dropdown_options.name as nature')
                                        ->get();     
        
        $expertServicesAcademic = ExpertServiceAcademic::leftJoin('expert_service_academic_documents', 'expert_service_academic_documents.expert_service_academic_id', 'expert_service_academics.id')
                                        ->where('expert_service_academic_documents.expert_service_academic_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_academics.classification')
                                        ->select('expert_service_academics.*', 'dropdown_options.name as classification')
                                        ->get();

        $extensionServices = ExtensionService::leftJoin('extension_service_documents', 'extension_service_documents.extension_service_id', 'extension_services.id')
                                        ->where('extension_service_documents.extension_service_id', null)
                                        ->where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'extension_services.status')
                                        ->select('extension_services.*', 'dropdown_options.name as status')
                                        ->get();
        
        $partnerships = Partnership::leftJoin('partnership_documents', 'partnership_documents.partnership_id', 'partnerships.id')
                                    ->where('partnership_documents.partnership_id', null)
                                    ->where('user_id', auth()->id())
                                    ->orderBy('partnerships.updated_at', 'desc')
                                    ->get();

        $mobilities = Mobility::leftJoin('mobility_documents', 'mobility_documents.mobility_id', 'mobilities.id')
                                    ->where('mobility_documents.mobility_id', null)
                                    ->where('user_id', auth()->id())
                                    ->orderBy('mobilities.updated_at', 'desc')
                                    ->get();
        
        $outreach_programs = OutreachProgram::leftJoin('outreach_program_documents', 'outreach_program_documents.outreach_program_id', 'outreach_programs.id')
                                    ->where('outreach_program_documents.outreach_program_id', null)
                                    ->where('user_id', auth()->id())
                                    ->orderBy('outreach_programs.updated_at', 'desc')
                                    ->get();

        
        // SELECT *
        //         FROM `form`
        //         LEFT JOIN `documentRef` ON `form`.id = `documentRef`.formForeignIdName
        //         WHERE `documentRef`.formForeignIdName IS NULL;
        return view('submissions.incomplete', compact('researches', 'inventions', 'syllabi', 'allRtmmi', 'student_awards', 'student_trainings',
                    'viable_projects', 'college_department_awards', 'technical_extensions',
                    'expertServicesConsultant', 'expertServicesConference', 'expertServicesAcademic',
                    'extensionServices', 'partnerships', 'mobilities', 'outreach_programs'));
    }
}
