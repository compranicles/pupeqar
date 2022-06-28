<?php

namespace App\Http\Controllers\Submissions;

use App\Http\Controllers\{
    Controller,
    Reports\ReportDataController,
    Maintenances\LockController
};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Chairperson,
    CollegeDepartmentAward,
    CollegeDepartmentAwardDocument,
    Dean,
    ExpertServiceAcademic,
    ExpertServiceAcademicDocument,
    ExpertServiceConference,
    ExpertServiceConferenceDocument,
    ExpertServiceConsultant,
    ExpertServiceConsultantDocument,
    ExtensionService,
    ExtensionServiceDocument,
    FacultyExtensionist,
    FacultyResearcher,
    Invention,
    InventionDocument,
    LogActivity,
    Mobility,
    MobilityDocument,
    OutreachProgram,
    OutreachProgramDocument,
    Partnership,
    PartnershipDocument,
    Reference,
    ReferenceDocument,
    Report,
    Request as RequestModel,
    RequestDocument,
    Research,
    ResearchCitation,
    ResearchDocument,
    ResearchPresentation,
    ResearchPublication,
    ResearchUtilization,
    SectorHead,
    StudentAward,
    StudentAwardDocument,
    StudentTraining,
    StudentTrainingDocument,
    Syllabus,
    SyllabusDocument,
    TechnicalExtension,
    TechnicalExtensionDocument,
    TemporaryFile,
    ViableProject,
    ViableProjectDocument,
    Authentication\UserRole,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    Maintenance\ReportCategory,
    AdminSpecialTask,
    AdminSpecialTaskDocument,
    SpecialTask,
    SpecialTaskDocument,
    AttendanceFunction,
};


class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collegeID = "all";

        $currentQuarterYear = Quarter::find(1);

        $totalReports = Report::where('user_id', auth()->id())
                            ->where('report_quarter', $currentQuarterYear->current_quarter)
                            ->where('report_year', $currentQuarterYear->current_year)
                            ->count();

        $report_tables = ReportCategory::all();
        // dd($report_tables);
        $report_array = [];
        $report_document_checker = [];
        $checker_array = [];
        foreach($report_tables as $table){
            switch($table->id){
                case '1':
                    $tempdata = [];
                    $data = Research::select(
                                        'research.id',
                                        'research.research_code',
                                        'research.title',
                                        'research.updated_at',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification', 'research.updated_at')
                                    ->join('colleges', 'colleges.id', 'research.college_id')->where('research.user_id', auth()->id())
                                    ->orderBy('research.updated_at', 'desc')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 1)->where('user_id', auth()->id())->exists()) {
                            if(
                                Report::join('research', 'research.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 1)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];

                    break;
                case '2':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_completes.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research.title',
                                        'research_completes.updated_at',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->join('research_completes', 'research_completes.research_code', 'research.research_code')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 2)->where('user_id', auth()->id())->exists()) {
                            if(
                                Report::join('research_completes', 'research_completes.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 2)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '3':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_publications.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research_publications.updated_at',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                            ->join('colleges', 'colleges.id', 'research.college_id')
                            ->join('research_publications', 'research_publications.research_code', 'research.research_code')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 3)->where('reports.user_id', auth()->id())->exists() ) {
                            if(
                                Report::join('research_publications', 'research_publications.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 3)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '4':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_presentations.id as id',
                                        'research_presentations.updated_at',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name',
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->join('research_presentations', 'research_presentations.research_code', 'research.research_code')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 4)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_presentations', 'research_presentations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 4)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }

                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '5':
                    $tempdata = [];
                    $data = ResearchCitation::select(
                                                'research_citations.id as id',
                                                'research.id as research_id',
                                                'research.research_code',
                                                'research_citations.updated_at',
                                                'research.title',
                                                'dropdown_options.name as classification_name',
                                                'colleges.name as college_name'
                                            )
                            ->join('research', 'research.research_code', 'research_citations.research_code')->where('research.user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'research.college_id')
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 5)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_citations', 'research_citations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 5)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_citation_id', $row->id)->where('research_form_id', $table->id)->
                                where('research_citation_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '6':
                    $tempdata = [];
                    $data = ResearchUtilization::select(
                                                    'research_utilizations.id as id',
                                                    'research.id as research_id',
                                                    'research.research_code',
                                                    'research_utilizations.updated_at',
                                                    'research.title',
                                                    'dropdown_options.name as classification_name',
                                                    'colleges.name as college_name'
                                                )
                                                ->join('research', 'research.research_code', 'research_utilizations.research_code')->where('research.user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'research.college_id')
                                                ->join('dropdown_options', 'dropdown_options.id', 'research.classification')->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 6)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_utilizations', 'research_utilizations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 6)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_utilization_id', $row->id)->where('research_form_id', $table->id)->
                                where('research_utilization_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '7':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_copyrights.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research_copyrights.updated_at',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->join('research_copyrights', 'research_copyrights.research_code', 'research.research_code')->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 7)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_copyrights', 'research_copyrights.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 7)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '8':
                    $data = Invention::select(
                                        'inventions.*',
                                        'colleges.name as college_name',
                                        'dropdown_options.name as classification_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'inventions.college_id')
                                    ->join('dropdown_options', 'dropdown_options.id', 'inventions.classification')
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 8)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('inventions', 'inventions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 8)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = InventionDocument::where('invention_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '9':
                    $data = ExpertServiceConsultant::select(
                                                        'expert_service_consultants.*',
                                                        'colleges.name as college_name',
                                                        'dropdown_options.name as classification_name'
                                                    )->where('user_id', auth()->id())
                                                    ->join('colleges', 'colleges.id', 'expert_service_consultants.college_id')
                                                    ->join('dropdown_options', 'dropdown_options.id', 'expert_service_consultants.classification')
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 9)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_consultants', 'expert_service_consultants.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 9)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '10':
                    $data = ExpertServiceConference::select(
                                                        'expert_service_conferences.*',
                                                        'colleges.name as college_name',
                                                        'dropdown_options.name as nature_name'
                                                    )->where('user_id', auth()->id())
                                                    ->join('colleges', 'colleges.id', 'expert_service_conferences.college_id')
                                                    ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 10)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_conferences', 'expert_service_conferences.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 10)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceConferenceDocument::where('expert_service_conference_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '11':
                    $data = ExpertServiceAcademic::select(
                                                    'expert_service_academics.*',
                                                    'colleges.name as college_name',
                                                    'dropdown_options.name as classification_name'
                                                )->where('user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'expert_service_academics.college_id')
                                                ->join('dropdown_options', 'dropdown_options.id', 'expert_service_academics.classification')
                                                ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 11)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_academics', 'expert_service_academics.id', 'reports.report_reference_id')->where('reports.user_id', auth()->id())->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 11)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceAcademicDocument::where('expert_service_academic_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '12':
                    $data = ExtensionService::select(
                                                'extension_services.*',
                                                'colleges.name as college_name',
                                                'dropdown_options.name as nature_of_involvement_name'
                                            )->where('user_id', auth()->id())
                                            ->join('colleges', 'colleges.id', 'extension_services.college_id')
                                            ->join('dropdown_options', 'dropdown_options.id', 'extension_services.nature_of_involvement')
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 12)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('extension_services', 'extension_services.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 12)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExtensionServiceDocument::where('ext_code', $row->ext_code)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '13':
                    $data = Partnership::select(
                                            'partnerships.*',
                                            'colleges.name as college_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'partnerships.college_id')
                                        ->get();
                $tempdata = [];
                foreach($data as $row){
                    if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 13)->where('reports.user_id', auth()->id())->exists() ) {
                        if (
                            Report::join('partnerships', 'partnerships.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                            ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 13)->where('reports.created_at', '<=', $row->updated_at)->exists()
                        )
                            array_push($tempdata, $row);
                    }
                    else
                        array_push($tempdata, $row);
                }
                $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = PartnershipDocument::where('partnership_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '14':
                    $data = Mobility::select(
                                        'mobilities.*',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'mobilities.college_id')
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 14)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('mobilities', 'mobilities.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 14)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = MobilityDocument::where('mobility_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '15':
                    $data = Reference::select(
                                            'references.*',
                                            'colleges.name as college_name',
                                            'dropdown_options.name as category_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'references.college_id')
                                        ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 15)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('references', 'references.id', 'reports.report_reference_id')->where('reports.user_id', auth()->id())->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 15)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ReferenceDocument::where('reference_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '16':
                    $data = Syllabus::select(
                                        'syllabi.*',
                                        'colleges.name as college_name',
                                        'dropdown_options.name as assigned_task_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'syllabi.college_id')
                                    ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 16)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('syllabi', 'syllabi.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 16)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SyllabusDocument::where('syllabus_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '17':
                    $data = RequestModel::select(
                                            'requests.*',
                                            'colleges.name as college_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'requests.college_id')
                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 17)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('requests', 'requests.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 17)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = RequestDocument::where('request_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '18':
                    $data = StudentAward::select(
                                            'student_awards.*'
                                        )->where('user_id', auth()->id())
                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 18)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('student_awards', 'student_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 18)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = StudentAwardDocument::where('student_award_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '19':
                    $data = StudentTraining::select(
                                                'student_trainings.*',
                                                'dropdown_options.name as classification_name'
                                            )->where('user_id', auth()->id())
                                            ->join('dropdown_options', 'dropdown_options.id', 'student_trainings.classification')
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 19)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('student_trainings', 'student_trainings.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 19)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = StudentTrainingDocument::where('student_training_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '20':
                    $data = ViableProject::select(
                                                'viable_projects.*'
                                            )->where('user_id', auth()->id())
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 20)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('viable_projects', 'viable_projects.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 20)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ViableProjectDocument::where('viable_project_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '21':
                    $data = CollegeDepartmentAward::select(
                                                        'college_department_awards.*'
                                                    )->where('user_id', auth()->id())
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 21)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('college_department_awards', 'college_department_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 21)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = CollegeDepartmentAwardDocument::where('college_department_award_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '22':
                    $data = OutreachProgram::select(
                                                'outreach_programs.*'
                                            )->where('user_id', auth()->id())
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 22)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('outreach_programs', 'outreach_programs.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 22)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = OutreachProgramDocument::where('outreach_program_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '23':
                    $data = TechnicalExtension::select(
                                                    'technical_extensions.*',
                                                    'dropdown_options.name as classification_of_adoptor_name'
                                                )->where('user_id', auth()->id())
                                                ->join('dropdown_options', 'dropdown_options.id', 'technical_extensions.classification_of_adoptor')
                                                ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 23)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('technical_extensions', 'technical_extensions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 23)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = TechnicalExtensionDocument::where('technical_extension_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '29':
                    $data = AdminSpecialTask::where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 29)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('admin_special_tasks', 'admin_special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 29)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = AdminSpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '30':
                    $data = SpecialTask::where('commitment_measure', 285)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 30)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 30)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '31':
                    $data = SpecialTask::where('commitment_measure', 286)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 31)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 31)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '32':
                    $data = SpecialTask::where('commitment_measure', 287)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 32)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 32)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                default:
                    $report_array[$table->id] = [];
                    $report_document_checker[$table->id] = [];
                    $checker_array = [];
                    break;
            }

        }

        // dd($report_document_checker[29][3]);
        $colleges = College::select('colleges.name', 'colleges.id')
                                ->whereIn('colleges.id', Research::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Invention::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceConsultant::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceConference::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceAcademic::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExtensionService::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Reference::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Syllabus::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Partnership::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Mobility::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', RequestModel::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $role = 'admin';
        if(in_array('1', $roles))
            $role = 'faculty';


        return view('submissions.index', compact('report_tables', 'report_array' , 'report_document_checker',  'colleges', 'collegeID', 'currentQuarterYear', 'totalReports', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report_controller = new ReportDataController;
        $user_id = auth()->id();
        $currentQuarterYear = Quarter::find(1);


        if($request->has('report_values')){
            $report_details;
            $reportColumns;
            $reportValues;
            $failedToSubmit = 0;
            $successToSubmit = 0;
            foreach($request->report_values as $report_value){
                $report_values_array = explode(',', $report_value); // 0 => research_code , 1 => report_category, 2 => id, 3 => research_id
                // dd($report_values_array);
                switch($report_values_array[1]){
                    case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                        if ($report_values_array[1] == 1) {

                            $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                        }
                        else {
                            $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[3])->first();
                            $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();

                        }
                        $reportColumns = collect($report_controller->getColumnDataPerReportCategory($report_values_array[1]));
                        if($report_values_array[1] == 5){
                            $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                            $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                        }
                        elseif($report_values_array[1] == 6){
                            $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                            $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                        }
                        elseif(($report_values_array[1] <= 4 || $report_values_array[1] == 7 )){
                            $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                            $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                        }
                        $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());

                        Report::where('report_reference_id', $report_values_array[2])
                            ->where('report_code', $report_values_array[0])
                            ->where('report_category_id', $report_values_array[1])
                            ->where('user_id', auth()->id())
                            ->where('report_quarter', $currentQuarterYear->current_quarter)
                            ->where('report_year', $currentQuarterYear->current_year)
                            ->delete();
                        Report::create([
                            'user_id' =>  $user_id,
                            'sector_id' => $sector_id,
                            'college_id' => $collegeAndDepartment->college_id,
                            'department_id' => $collegeAndDepartment->department_id,
                            'report_category_id' => $report_values_array[1],
                            'report_code' => $report_values_array[0] ?? null,
                            'report_reference_id' => $report_values_array[2] ?? null,
                            'report_details' => json_encode($report_details),
                            'report_documents' => json_encode($report_documents),
                            'report_date' => date("Y-m-d", time()),
                            'report_quarter' => $currentQuarterYear->current_quarter,
                            'report_year' => $currentQuarterYear->current_year,
                        ]);
                        $successToSubmit++;

                    break;
                    case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: 
                            case 17: case 18: case 19: case 20: case 21: case 22: case 29: case 30: 
                            case 31: case 32:
                        switch($report_values_array[1]){
                            case 8:
                                $collegeAndDepartment = Invention::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 9:
                                $collegeAndDepartment = ExpertServiceConsultant::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 10:
                                $collegeAndDepartment = ExpertServiceConference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 11:
                                $collegeAndDepartment = ExpertServiceAcademic::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 12:
                                $collegeAndDepartment = ExtensionService::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 13:
                                $collegeAndDepartment = Partnership::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 14:
                                $collegeAndDepartment = Mobility::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 15:
                                $collegeAndDepartment = Reference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 16:
                                $collegeAndDepartment = Syllabus::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 17:
                                $collegeAndDepartment = RequestModel::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 18:
                                $collegeAndDepartment = StudentAward::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 19:
                                $collegeAndDepartment = StudentTraining::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 20:
                                $collegeAndDepartment = ViableProject::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 21:
                                $collegeAndDepartment = OutreachProgram::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            case 22:
                                $collegeAndDepartment = RequestModel::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 29:
                                $collegeAndDepartment = AdminSpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 30:
                                $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 31:
                                $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                            case 32:
                                $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                                $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                            break;
                        }
                        $reportColumns = collect($report_controller->getColumnDataPerReportCategory($report_values_array[1]));
                        $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                        $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                        $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());
                        Report::where('report_reference_id', $report_values_array[2])
                            ->where('report_code', $report_values_array[0])
                            ->where('report_category_id', $report_values_array[1])
                            ->where('user_id', auth()->id())
                            ->where('report_quarter', $currentQuarterYear->current_quarter)
                            ->where('report_year', $currentQuarterYear->current_year)
                            ->delete();
                        Report::create([
                            'user_id' =>  $user_id,
                            'sector_id' => $sector_id,
                            'college_id' => $collegeAndDepartment->college_id,
                            'department_id' => $collegeAndDepartment->department_id,
                            'report_category_id' => $report_values_array[1],
                            'report_code' => $report_values_array[0] ?? null,
                            'report_reference_id' => $report_values_array[2] ?? null,
                            'report_details' => json_encode($report_details),
                            'report_documents' => json_encode($report_documents),
                            'report_date' => date("Y-m-d", time()),
                            'report_quarter' => $currentQuarterYear->current_quarter,
                            'report_year' => $currentQuarterYear->current_year,
                        ]);
                        $successToSubmit++;

                    break;
                }

            }
        }
        \LogActivity::addToLog($successToSubmit.' accomplishments submitted.');

        return redirect()->route('to-finalize.index')->with('success', $successToSubmit.' accomplishment reports have been submitted. ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addDocument($id, $report_category_id){
        return view('add-document', compact('id', 'report_category_id'));
    }
    public function saveDocument(Request $request,$id, $report_category_id){
        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'DOC-'.$id.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    if($report_category_id == 8){
                        InventionDocument::create([
                            'invention_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 9){
                        ExpertServiceConsultantDocument::create([
                            'expert_service_consultant_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 10){
                        ExpertServiceConferenceDocument::create([
                            'expert_service_conference_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 11){
                        ExpertServiceAcademicDocument::create([
                            'expert_service_academic_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 12){
                        ExtensionServiceDocument::create([
                            'extension_service_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 13){
                        PartnershipDocument::create([
                            'partnership_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 14){
                        MobilityDocument::create([
                            'mobility_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 15){
                        ReferenceDocument::create([
                            'reference_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 16){
                        SyllabusDocument::create([
                            'syllabus_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 17){
                        RequestDocument::create([
                            'request_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 18){
                        StudentAwardDocument::create([
                            'student_award_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 19){
                        StudentTrainingDocument::create([
                            'student_training_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 20){
                        ViableProjectDocument::create([
                            'viable_project_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 21){
                        CollegeDepartmentAwardDocument::create([
                            'college_department_award_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 22){
                        OutreachProgramDocument::create([
                            'outreach_program_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 23){
                        TechnicalExtensionDocument::create([
                            'technical_extension_id' => $id,
                            'filename' => $fileName,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('to-finalize.index')->with('success', 'Document/s have been added');
    }

    public function getCollege($collegeID) {

        $currentQuarterYear = Quarter::find(1);

        $totalReports = Report::where('user_id', auth()->id())
                            ->where('report_quarter', $currentQuarterYear->current_quarter)
                            ->where('report_year', $currentQuarterYear->current_year)
                            ->count();

        if ($collegeID == 'all') {
            return redirect()->route('to-finalize.index');
        }
        $report_tables = ReportCategory::all();
        // dd($report_tables);
        $report_array = [];
        $report_document_checker = [];
        $checker_array = [];
        foreach($report_tables as $table){
            switch($table->id){
                case '1':
                    $tempdata = [];
                    $data = Research::select(
                                        'research.id',
                                        'research.research_code',
                                        'research.title',
                                        'research.updated_at',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification', 'research.updated_at')
                                    ->join('colleges', 'colleges.id', 'research.college_id')->where('research.user_id', auth()->id())
                                    ->where('research.college_id', $collegeID)
                                    ->orderBy('research.updated_at', 'desc')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 1)->where('reports.user_id', auth()->id())->exists()) {
                            if(
                                Report::join('research', 'research.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 1)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_id', $row->id)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];

                    break;
                case '2':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_completes.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research.title',
                                        'research_completes.updated_at',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->where('research.college_id', $collegeID)
                                    ->join('research_completes', 'research_completes.research_code', 'research.research_code')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 2)->where('reports.user_id', auth()->id())->exists()) {
                            if(
                                Report::join('research_completes', 'research_completes.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 2)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_id', $row->research_id)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '3':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_publications.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research_publications.updated_at',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                            ->join('colleges', 'colleges.id', 'research.college_id')
                            ->where('research.college_id', $collegeID)
                            ->join('research_publications', 'research_publications.research_code', 'research.research_code')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 3)->where('reports.user_id', auth()->id())->exists() ) {
                            if(
                                Report::join('research_publications', 'research_publications.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 3)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_id', $row->research_id)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '4':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_presentations.id as id',
                                        'research_presentations.updated_at',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name',
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->join('research_presentations', 'research_presentations.research_code', 'research.research_code')
                                    ->where('research.college_id', $collegeID)->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 4)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_presentations', 'research_presentations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 4)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }

                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_id', $row->research_id)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '5':
                    $tempdata = [];
                    $data = ResearchCitation::select(
                                                'research_citations.id as id',
                                                'research.id as research_id',
                                                'research.research_code',
                                                'research_citations.updated_at',
                                                'research.title',
                                                'dropdown_options.name as classification_name',
                                                'colleges.name as college_name'
                                            )
                            ->join('research', 'research.research_code', 'research_citations.research_code')->where('research.user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'research.college_id')
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                            ->where('research.college_id', $collegeID)->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 5)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_citations', 'research_citations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 5)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_citation_id', $row->id)->where('research_form_id', $table->id)->
                                where('research_citation_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '6':
                    $tempdata = [];
                    $data = ResearchUtilization::select(
                                                    'research_utilizations.id as id',
                                                    'research.id as research_id',
                                                    'research.research_code',
                                                    'research_utilizations.updated_at',
                                                    'research.title',
                                                    'dropdown_options.name as classification_name',
                                                    'colleges.name as college_name'
                                                )
                                                ->join('research', 'research.research_code', 'research_utilizations.research_code')->where('research.user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'research.college_id')
                                                ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                                ->where('research.college_id', $collegeID)->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 6)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_utilizations', 'research_utilizations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 6)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_utilization_id', $row->id)->where('research_form_id', $table->id)->
                                where('research_utilization_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '7':
                    $tempdata = [];
                    $data = Research::select(
                                        'research_copyrights.id as id',
                                        'research.id as research_id',
                                        'research.research_code',
                                        'research_copyrights.updated_at',
                                        'research.title',
                                        'dropdown_options.name as classification_name',
                                        'colleges.name as college_name'
                                    )->where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                    ->join('colleges', 'colleges.id', 'research.college_id')
                                    ->join('research_copyrights', 'research_copyrights.research_code', 'research.research_code')
                                    ->where('research.college_id', $collegeID)->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 7)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('research_copyrights', 'research_copyrights.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 7)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_id', $row->research_id)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '8':
                    $data = Invention::select(
                                        'inventions.*',
                                        'colleges.name as college_name',
                                        'dropdown_options.name as classification_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'inventions.college_id')
                                    ->join('dropdown_options', 'dropdown_options.id', 'inventions.classification')
                                    ->where('inventions.college_id', $collegeID)
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 8)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('inventions', 'inventions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 8)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = InventionDocument::where('invention_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '9':
                    $data = ExpertServiceConsultant::select(
                                                        'expert_service_consultants.*',
                                                        'colleges.name as college_name',
                                                        'dropdown_options.name as classification_name'
                                                    )->where('user_id', auth()->id())
                                                    ->join('colleges', 'colleges.id', 'expert_service_consultants.college_id')
                                                    ->join('dropdown_options', 'dropdown_options.id', 'expert_service_consultants.classification')
                                                    ->where('expert_service_consultants.college_id', $collegeID)
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 9)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_consultants', 'expert_service_consultants.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 9)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '10':
                    $data = ExpertServiceConference::select(
                                                        'expert_service_conferences.*',
                                                        'colleges.name as college_name',
                                                        'dropdown_options.name as nature_name'
                                                    )->where('user_id', auth()->id())
                                                    ->join('colleges', 'colleges.id', 'expert_service_conferences.college_id')
                                                    ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                                    ->where('expert_service_conferences.college_id', $collegeID)
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 10)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_conferences', 'expert_service_conferences.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 10)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceConferenceDocument::where('expert_service_conference_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '11':
                    $data = ExpertServiceAcademic::select(
                                                    'expert_service_academics.*',
                                                    'colleges.name as college_name',
                                                    'dropdown_options.name as classification_name'
                                                )->where('user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'expert_service_academics.college_id')
                                                ->join('dropdown_options', 'dropdown_options.id', 'expert_service_academics.classification')
                                                ->where('expert_service_academics.college_id', $collegeID)
                                                ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 11)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('expert_service_academics', 'expert_service_academics.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 11)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExpertServiceAcademicDocument::where('expert_service_academic_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '12':
                    $data = ExtensionService::select(
                                                'extension_services.*',
                                                'colleges.name as college_name',
                                                'dropdown_options.name as nature_of_involvement_name'
                                            )->where('user_id', auth()->id())
                                            ->join('colleges', 'colleges.id', 'extension_services.college_id')
                                            ->join('dropdown_options', 'dropdown_options.id', 'extension_services.nature_of_involvement')
                                            ->where('extension_services.college_id', $collegeID)
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 12)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('extension_services', 'extension_services.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 12)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ExtensionServiceDocument::where('extension_service_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '13':
                    $data = Partnership::select(
                                            'partnerships.*',
                                            'colleges.name as college_name',
                                            'dropdown_options.name as collab_nature_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'partnerships.college_id')
                                        ->join('dropdown_options', 'dropdown_options.id', 'partnerships.collab_nature')
                                        ->where('partnerships.college_id', $collegeID)

                                        ->get();
                $tempdata = [];
                foreach($data as $row){
                    if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 13)->where('reports.user_id', auth()->id())->exists() ) {
                        if (
                            Report::join('partnerships', 'partnerships.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                            ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 13)->where('reports.created_at', '<=', $row->updated_at)->exists()
                        )
                            array_push($tempdata, $row);
                    }
                    else
                        array_push($tempdata, $row);
                }
                $data = $tempdata;

                    if($data != null){
                        foreach($data as $row){
                            $checker = PartnershipDocument::where('partnership_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '14':
                    $data = Mobility::select(
                                        'mobilities.*',
                                        'colleges.name as college_name',
                                        'dropdown_options.name as type_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'mobilities.college_id')
                                    ->join('dropdown_options', 'dropdown_options.id', 'mobilities.type')
                                    ->where('mobilities.college_id', $collegeID)

                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 14)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('mobilities', 'mobilities.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 14)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = MobilityDocument::where('mobility_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '15':
                    $data = Reference::select(
                                            'references.*',
                                            'colleges.name as college_name',
                                            'dropdown_options.name as category_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'references.college_id')
                                        ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                        ->where('references.college_id', $collegeID)

                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 15)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('references', 'references.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 15)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ReferenceDocument::where('reference_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '16':
                    $data = Syllabus::select(
                                        'syllabi.*',
                                        'colleges.name as college_name',
                                        'dropdown_options.name as assigned_task_name'
                                    )->where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'syllabi.college_id')
                                    ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                                    ->where('syllabi.college_id', $collegeID)

                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 16)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('syllabi', 'syllabi.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 16)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SyllabusDocument::where('syllabus_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '17':
                    $data = RequestModel::select(
                                            'requests.*',
                                            'colleges.name as college_name'
                                        )->where('user_id', auth()->id())
                                        ->join('colleges', 'colleges.id', 'requests.college_id')
                                        ->where('requests.college_id', $collegeID)
                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 17)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('requests', 'requests.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 17)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = RequestDocument::where('request_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '18':
                    $data = StudentAward::select(
                                            'student_awards.*'
                                        )->where('user_id', auth()->id())
                                        ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 18)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('student_awards', 'student_awards.id', 'reports.report_reference_id')->where('reports.user_id', auth()->id())->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 18)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = StudentAwardDocument::where('student_award_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '19':
                    $data = StudentTraining::select(
                                                'student_trainings.*',
                                                'dropdown_options.name as classification_name'
                                            )->where('user_id', auth()->id())
                                            ->join('dropdown_options', 'dropdown_options.id', 'student_trainings.classification')
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 19)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('student_trainings', 'student_trainings.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 19)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = StudentTrainingDocument::where('student_training_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '20':
                    $data = ViableProject::select(
                                                'viable_projects.*'
                                            )->where('user_id', auth()->id())
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 20)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('viable_projects', 'viable_projects.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 20)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = ViableProjectDocument::where('viable_project_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '21':
                    $data = CollegeDepartmentAward::select(
                                                        'college_department_awards.*'
                                                    )->where('user_id', auth()->id())
                                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 21)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('college_department_awards', 'college_department_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 21)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = CollegeDepartmentAwardDocument::where('college_department_award_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '22':
                    $data = OutreachProgram::select(
                                                'outreach_programs.*'
                                            )->where('user_id', auth()->id())
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 22)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('outreach_programs', 'outreach_programs.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 22)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = OutreachProgramDocument::where('outreach_program_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '23':
                    $data = TechnicalExtension::select(
                                                    'technical_extensions.*',
                                                    'dropdown_options.name as classification_of_adoptor_name'
                                                )->where('user_id', auth()->id())
                                                ->join('dropdown_options', 'dropdown_options.id', 'technical_extensions.classification_of_adoptor')

                                                ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 23)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('technical_extensions', 'technical_extensions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 23)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = TechnicalExtensionDocument::where('technical_extension_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '29':
                    $data = AdminSpecialTask::where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 29)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('admin_special_tasks', 'admin_special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 29)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = AdminSpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '30':
                    $data = SpecialTask::where('commitment_measure', 285)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 30)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 30)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '31':
                    $data = SpecialTask::where('commitment_measure', 286)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 31)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 31)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '32':
                    $data = SpecialTask::where('commitment_measure', 287)->where('user_id', auth()->id())->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 32)->where('reports.user_id', auth()->id())->exists() ) {
                            if (
                                Report::join('special_tasks', 'special_tasks.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.user_id', auth()->id())->where('reports.report_category_id', 32)->where('reports.created_at', '<=', $row->updated_at)->exists()
                            )
                                array_push($tempdata, $row);
                        }
                        else
                            array_push($tempdata, $row);
                    }
                    $data = $tempdata;
                    if($data != null){
                        foreach($data as $row){
                            $checker = SpecialTaskDocument::where('special_task_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                default:
                    $report_array[$table->id] = [];
                    $report_document_checker[$table->id] = [];
                    $checker_array = [];
                    break;
            }

        }

        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments_nav = [];
        $colleges_nav = [];
        $sectors_nav = [];
        $departmentsResearch_nav = [];
        $departmentsExtension_nav = [];

        if(in_array(5, $roles)){
            $departments_nav = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges_nav = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'deans.college_id')->get();
        }
        if(in_array(7, $roles)){
            $sectors_nav = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'sector_head.sector_id')->get();
        }
        if(in_array(10, $roles)){
            $departmentsResearch_nav = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension_nav = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
        }


        $colleges = College::select('colleges.name', 'colleges.id')
                                ->whereIn('colleges.id', Research::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Invention::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceConsultant::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceConference::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExpertServiceAcademic::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', ExtensionService::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Reference::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Syllabus::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Partnership::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', Mobility::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->orWhereIn('colleges.id', RequestModel::where('user_id', auth()->id())->pluck('college_id')->all())
                                ->get();
        // dd($reported_accomplishments);

        // dd($report_array);
        $role = 'admin';
        if(in_array('1', $roles))
            $role = 'faculty';

        return view('submissions.index', compact('roles', 'departments_nav', 'colleges_nav', 'report_tables', 'report_array' , 'report_document_checker',  'colleges', 'collegeID', 'currentQuarterYear', 'totalReports', 'sectors_nav', 'departmentsResearch_nav','departmentsExtension_nav', 'role'));
    }

    public function check($report_category_id, $accomplishment_id){
        if(LockController::isLocked($accomplishment_id, $report_category_id))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        $reportdata = new ReportDataController;
        if(empty($reportdata->getDocuments($report_category_id, $accomplishment_id)))
            return redirect()->back()->with('cannot_access', 'Missing Supporting Documents.');

        $research_code = '*';
        $research_id = '*';
        if($report_category_id >= 1 && $report_category_id <= 7){
            $research_code = Research::where('id', $accomplishment_id)->pluck('research_code')->first();
            if($report_category_id == 2)
                ResearchComplete::where('id', $accomplishment_id)->pluck('research_id')->first();
            if($report_category_id == 3)
                ResearchPublication::where('id', $accomplishment_id)->pluck('research_id')->first();
            if($report_category_id == 4)
                ResearchPresentation::where('id', $accomplishment_id)->pluck('research_id')->first();
            if($report_category_id == 5)
                ResearchCitation::where('id', $accomplishment_id)->pluck('research_id')->first();
            if($report_category_id == 6)
                ResearchUtilization::where('id', $accomplishment_id)->pluck('research_id')->first();
            if($report_category_id == 7)
                ResearchCopyright::where('id', $accomplishment_id)->pluck('research_id')->first();
        }
        if($this->submitAlternate($report_category_id, $accomplishment_id, $research_code, $research_id)) 
            return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');
        return redirect()->back()->with('cannot_submit', 'Fail to submit accomplishment');
    }

    public function submitAlternate($report_category_id, $accomplishment_id, $research_code, $research_id){
        $report_controller = new ReportDataController;
        $user_id = auth()->id();
        $currentQuarterYear = Quarter::find(1);

        $report_details;
        $reportColumns;
        $reportValues;
        $failedToSubmit = 0;
        $successToSubmit = 0;
        $report_values_array = [$research_code, $report_category_id, $accomplishment_id, $research_id]; // 0 => research_code , 1 => report_category, 2 => id, 3 => research_id
        // dd($report_values_array);
        switch($report_values_array[1]){
            case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                if ($report_values_array[1] == 1) {

                    $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                    $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                }
                else {
                    $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                    $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();

                }
                $reportColumns = collect($report_controller->getColumnDataPerReportCategory($report_values_array[1]));
                if($report_values_array[1] == 5){
                    $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                    $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                }
                elseif($report_values_array[1] == 6){
                    $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                    $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                }
                elseif(($report_values_array[1] <= 4 || $report_values_array[1] == 7 )){
                    $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                    $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                }
                $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());

                Report::where('report_reference_id', $report_values_array[2])
                    ->where('report_code', $report_values_array[0])
                    ->where('report_category_id', $report_values_array[1])
                    ->where('user_id', auth()->id())
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->delete();
                Report::create([
                    'user_id' =>  $user_id,
                    'sector_id' => $sector_id,
                    'college_id' => $collegeAndDepartment->college_id,
                    'department_id' => $collegeAndDepartment->department_id,
                    'report_category_id' => $report_values_array[1],
                    'report_code' => $report_values_array[0] ?? null,
                    'report_reference_id' => $report_values_array[2] ?? null,
                    'report_details' => json_encode($report_details),
                    'report_documents' => json_encode($report_documents),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
                $successToSubmit++;

            break;
            case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 29: case 30: case 31: case 32: case 33:
                switch($report_values_array[1]){
                    case 8:
                        $collegeAndDepartment = Invention::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 9:
                        $collegeAndDepartment = ExpertServiceConsultant::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 10:
                        $collegeAndDepartment = ExpertServiceConference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 11:
                        $collegeAndDepartment = ExpertServiceAcademic::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 12:
                        $collegeAndDepartment = ExtensionService::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 13:
                        $collegeAndDepartment = Partnership::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 14:
                        $collegeAndDepartment = Mobility::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 15:
                        $collegeAndDepartment = Reference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 16:
                        $collegeAndDepartment = Syllabus::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    case 16:
                        $collegeAndDepartment = Syllabus::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 29:
                        $collegeAndDepartment = AdminSpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 30:
                        $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 31:
                        $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 32:
                        $collegeAndDepartment = SpecialTask::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 33:
                        $collegeAndDepartment = AttendanceFunction::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                    case 34:
                        $collegeAndDepartment = IntraMobility::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    case 38:
                        $collegeAndDepartment = OtherAccomplishment::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                        $sector_id = College::where('id', $collegeAndDepartment->college_id)->pluck('sector_id')->first();
                    break;
                }
                $reportColumns = collect($report_controller->getColumnDataPerReportCategory($report_values_array[1]));
                $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());
                Report::where('report_reference_id', $report_values_array[2])
                    ->where('report_code', $report_values_array[0])
                    ->where('report_category_id', $report_values_array[1])
                    ->where('user_id', auth()->id())
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->delete();
                Report::create([
                    'user_id' =>  $user_id,
                    'sector_id' => $sector_id,
                    'college_id' => $collegeAndDepartment->college_id,
                    'department_id' => $collegeAndDepartment->department_id,
                    'report_category_id' => $report_values_array[1],
                    'report_code' => $report_values_array[0] ?? null,
                    'report_reference_id' => $report_values_array[2] ?? null,
                    'report_details' => json_encode($report_details),
                    'report_documents' => json_encode($report_documents),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
                $successToSubmit++;

            break;
            case 17: case 18: case 19: case 20: case 21: case 22: case 23:
                //role and department/ college id
                $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
                $department_id = '';
                $college_id = '';
                $sector_id = '';
                if(in_array(5, $roles)){
                    $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
                    $college_id = Department::where('id', $department_id)->pluck('college_id')->first();
                    $sector_id = College::where('id', $college_id)->pluck('sector_id')->first();
                }
                if(in_array(6, $roles)){
                    $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
                    $sector_id = College::where('id', $college_id)->pluck('sector_id')->first();
                }
                $reportColumns = collect($report_controller->getColumnDataPerReportCategory($report_values_array[1]));
                $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[2]));
                $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[2]);
                $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());
                if(in_array(5, $roles)){

                    Report::where('report_reference_id', $report_values_array[2])
                        ->where('report_code', $report_values_array[0])
                        ->where('report_category_id', $report_values_array[1])
                        ->where('user_id', auth()->id())
                        ->where('report_quarter', $currentQuarterYear->current_quarter)
                        ->where('report_year', $currentQuarterYear->current_year)
                        ->delete();
                    Report::create([
                        'user_id' =>  $user_id,
                        'sector_id' => $sector_id,
                        'college_id' => $college_id ?? null,
                        'department_id' => $department_id ?? null,
                        'report_category_id' => $report_values_array[1],
                        'report_code' => $report_values_array[0] ?? null,
                        'report_reference_id' => $report_values_array[2] ?? null,
                        'report_details' => json_encode($report_details),
                        'report_documents' => json_encode($report_documents),
                        'report_date' => date("Y-m-d", time()),
                        'chairperson_approval' => 1,
                        'report_quarter' => $currentQuarterYear->current_quarter,
                        'report_year' => $currentQuarterYear->current_year,
                    ]);

                    $successToSubmit++;
                }
                if(in_array(6, $roles)){

                    Report::where('report_reference_id', $report_values_array[2])
                        ->where('report_code', $report_values_array[0])
                        ->where('report_category_id', $report_values_array[1])
                        ->where('user_id', auth()->id())
                        ->where('report_quarter', $currentQuarterYear->current_quarter)
                        ->where('report_year', $currentQuarterYear->current_year)
                        ->delete();
                    Report::create([
                        'user_id' =>  $user_id,
                        'sector_id' => $sector_id ?? null,
                        'college_id' => $college_id ?? null,
                        'department_id' => $department_id ?? null,
                        'report_category_id' => $report_values_array[1],
                        'report_code' => $report_values_array[0] ?? null,
                        'report_reference_id' => $report_values_array[2] ?? null,
                        'report_details' => json_encode($report_details),
                        'report_documents' => json_encode($report_documents),
                        'report_date' => date("Y-m-d", time()),
                        'chairperson_approval' => 1,
                        'dean_approval' => 1,
                        'report_quarter' => $currentQuarterYear->current_quarter,
                        'report_year' => $currentQuarterYear->current_year,
                    ]);
                    $successToSubmit++;
                }
            break;
        }
        \LogActivity::addToLog('An accomplishment submitted.');

        return true;
    }
}
