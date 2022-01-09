<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Mobility;
use App\Models\Research;
use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\Chairperson;
use App\Models\Partnership;
use Illuminate\Support\Arr;
use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ViableProject;
use App\Models\OutreachProgram;
use App\Models\RequestDocument;
use App\Models\StudentTraining;
use App\Models\ExtensionService;
use App\Models\MobilityDocument;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use App\Models\SyllabusDocument;
use App\Models\InventionDocument;
use App\Models\ReferenceDocument;
use App\Models\TechnicalExtension;
use App\Models\PartnershipDocument;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\StudentAwardDocument;
use App\Models\ExpertServiceAcademic;
use App\Models\ViableProjectDocument;
use App\Models\CollegeDepartmentAward;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use App\Models\OutreachProgramDocument;
use App\Models\Request as RequestModel;
use App\Models\StudentTrainingDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\ExtensionServiceDocument;
use App\Models\Maintenance\ReportCategory;
use App\Models\TechnicalExtensionDocument;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\CollegeDepartmentAwardDocument;
use App\Models\ExpertServiceConferenceDocument;
use App\Models\ExpertServiceConsultantDocument;
use App\Http\Controllers\Reports\ReportController;
use App\Models\Maintenance\College;



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

        $currentMonth = date('m');

        $quarter = 0;
        if ($currentMonth <= 3 && $currentMonth >= 1) {
            $quarter = 1;
            $totalReports = Report::whereMonth('report_date', '>=', 1)->whereMonth('report_date', '<=', 3)
                    ->where('user_id', auth()->id())->whereYear('report_date', date('Y'))->count();

        }
        if ($currentMonth <= 6 && $currentMonth >= 4) {
            $quarter = 2;
            $totalReports = Report::whereMonth('report_date', '>=', 4)->whereMonth('report_date', '<=', 6)
                    ->where('user_id', auth()->id())->whereYear('report_date', date('Y'))->count();
        }
        if ($currentMonth <= 9 && $currentMonth >= 7) {
            $totalReports = Report::whereMonth('report_date', '>=', 7)->whereMonth('report_date', '<=', 9)
                    ->where('user_id', auth()->id())->whereYear('report_date', date('Y'))->count();
        }
        if ($currentMonth <= 12 && $currentMonth >= 10) {
            $quarter = 4;
            $totalReports = Report::whereMonth('report_date', '>=', 10)->whereMonth('report_date', '<=', 12)
                    ->where('user_id', auth()->id())->whereYear('report_date', date('Y'))->count();
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
                                    ->orderBy('research.updated_at', 'desc')->get();
                    
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 1)->exists()) {
                            if( 
                                Report::join('research', 'research.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 1)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_completes', 'research_completes.research_id', 'research.id')->get();
                            
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 2)->exists()) {
                            if( 
                                Report::join('research_completes', 'research_completes.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 2)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                            ->join('research_publications', 'research_publications.research_id', 'research.id')->get();
                    
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 3)->exists() ) {
                            if( 
                                Report::join('research_publications', 'research_publications.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 3)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_presentations', 'research_presentations.research_id', 'research.id')->get();
                            
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 4)->exists() ) {
                            if ( 
                                Report::join('research_presentations', 'research_presentations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 4)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                            ->join('research', 'research.id', 'research_citations.research_id')->where('research.user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'research.college_id')  
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 5)->exists() ) {
                            if ( 
                                Report::join('research_citations', 'research_citations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 5)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                                ->join('research', 'research.id', 'research_utilizations.research_id')->where('research.user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'research.college_id')  
                                                ->join('dropdown_options', 'dropdown_options.id', 'research.classification')->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 6)->exists() ) {
                            if ( 
                                Report::join('research_utilizations', 'research_utilizations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 6)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_copyrights', 'research_copyrights.research_id', 'research.id')->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 7)->exists() ) {
                            if ( 
                                Report::join('research_copyrights', 'research_copyrights.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 7)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 8)->exists() ) {
                            if ( 
                                Report::join('inventions', 'inventions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 8)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 9)->exists() ) {
                            if ( 
                                Report::join('expert_service_consultants', 'expert_service_consultants.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 9)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 10)->exists() ) {
                            if ( 
                                Report::join('expert_service_conferences', 'expert_service_conferences.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 10)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 11)->exists() ) {
                            if ( 
                                Report::join('expert_service_academics', 'expert_service_academics.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 12)->exists() ) {
                            if ( 
                                Report::join('extension_services', 'extension_services.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 12)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                        ->get();
                $tempdata = [];
                foreach($data as $row){
                    if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 13)->exists() ) {
                        if ( 
                            Report::join('partnerships', 'partnerships.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 13)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 14)->exists() ) {
                            if ( 
                                Report::join('mobilities', 'mobilities.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 14)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 15)->exists() ) {
                            if ( 
                                Report::join('references', 'references.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 16)->exists() ) {
                            if ( 
                                Report::join('syllabi', 'syllabi.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 16)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 17)->exists() ) {
                            if ( 
                                Report::join('requests', 'requests.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 17)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 18)->exists() ) {
                            if ( 
                                Report::join('student_awards', 'student_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 19)->exists() ) {
                            if ( 
                                Report::join('student_trainings', 'student_trainings.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 19)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 20)->exists() ) {
                            if ( 
                                Report::join('viable_projects', 'viable_projects.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 20)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 21)->exists() ) {
                            if ( 
                                Report::join('college_department_awards', 'college_department_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 21)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 22)->exists() ) {
                            if ( 
                                Report::join('outreach_programs', 'outreach_programs.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 22)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 23)->exists() ) {
                            if ( 
                                Report::join('technical_extensions', 'technical_extensions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 23)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
            }
            
        }

        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
        }
        // dd($department_id);
        if(in_array(6, $roles)){
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
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
        return view('submissions.index', compact('report_tables', 'report_array' , 'report_document_checker', 'roles', 'department_id', 'college_id', 'colleges', 'collegeID', 'quarter', 'totalReports'));
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
        $report_controller = new ReportController;
        $user_id = auth()->id();

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
                        $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[3])->first();
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
                            ->delete();
                        Report::create([
                            'user_id' =>  $user_id,
                            'college_id' => $collegeAndDepartment->college_id,
                            'department_id' => $collegeAndDepartment->department_id,
                            'report_category_id' => $report_values_array[1],
                            'report_code' => $report_values_array[0] ?? null,
                            'report_reference_id' => $report_values_array[2] ?? null,
                            'report_details' => json_encode($report_details),
                            'report_documents' => json_encode($report_documents),
                            'report_date' => date("Y-m-d", time()),
                        ]);
                        $successToSubmit++;
                    
                    break;
                    case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16:
                        switch($report_values_array[1]){
                            case 8:
                                $collegeAndDepartment = Invention::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 9:
                                $collegeAndDepartment = ExpertServiceConsultant::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 10:
                                $collegeAndDepartment = ExpertServiceConference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 11:
                                $collegeAndDepartment = ExpertServiceAcademic::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 12:
                                $collegeAndDepartment = ExtensionService::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 13:
                                $collegeAndDepartment = Partnership::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 14:
                                $collegeAndDepartment = Mobility::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 15:
                                $collegeAndDepartment = Reference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            case 16:
                                $collegeAndDepartment = Syllabus::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
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
                            ->delete();
                        Report::create([
                            'user_id' =>  $user_id,
                            'college_id' => $collegeAndDepartment->college_id,
                            'department_id' => $collegeAndDepartment->department_id,
                            'report_category_id' => $report_values_array[1],
                            'report_code' => $report_values_array[0] ?? null,
                            'report_reference_id' => $report_values_array[2] ?? null,
                            'report_details' => json_encode($report_details),
                            'report_documents' => json_encode($report_documents),
                            'report_date' => date("Y-m-d", time()),
                        ]);
                        $successToSubmit++;
                    
                    break;
                    case 17: case 18: case 19: case 20: case 21: case 22: case 23:
                        //role and department/ college id
                        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
                        $department_id = '';
                        $college_id = '';
                        if(in_array(5, $roles)){
                            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
                            $college_id = Department::where('id', $department_id)->pluck('college_id')->first();
                        }
                        if(in_array(6, $roles)){
                            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
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
                                ->delete();
                            Report::create([
                                'user_id' =>  $user_id,
                                'college_id' => $college_id ?? null,
                                'department_id' => $department_id ?? null,
                                'report_category_id' => $report_values_array[1],
                                'report_code' => $report_values_array[0] ?? null,
                                'report_reference_id' => $report_values_array[2] ?? null,
                                'report_details' => json_encode($report_details),
                                'report_documents' => json_encode($report_documents),
                                'report_date' => date("Y-m-d", time()),
                                'chairperson_approval' => 1    
                            ]);

                            $successToSubmit++;
                        }
                        if(in_array(6, $roles)){
                            
                            Report::where('report_reference_id', $report_values_array[2])
                                ->where('report_code', $report_values_array[0])
                                ->where('report_category_id', $report_values_array[1])
                                ->where('user_id', auth()->id())
                                ->delete();
                            Report::create([
                                'user_id' =>  $user_id,
                                'college_id' => $college_id ?? null,
                                'department_id' => $department_id ?? null,
                                'report_category_id' => $report_values_array[1],
                                'report_code' => $report_values_array[0] ?? null,
                                'report_reference_id' => $report_values_array[2] ?? null,
                                'report_details' => json_encode($report_details),
                                'report_documents' => json_encode($report_documents),
                                'report_date' => date("Y-m-d", time()),
                                'chairperson_approval' => 1,
                                'dean_approval' => 1

                            ]);
                            $successToSubmit++;
                        }
                    break;
                }
                
            }
        }

        return redirect()->route('to-finalize.index')->with('success', $successToSubmit.' accomplishment reports have been submitted. '.$failedToSubmit.' accomplishment reports have been failed to submit.');
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 1)->exists()) {
                            if( 
                                Report::join('research', 'research.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 1)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_completes', 'research_completes.research_id', 'research.id')->get();
                            
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 2)->exists()) {
                            if( 
                                Report::join('research_completes', 'research_completes.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 2)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                            ->join('research_publications', 'research_publications.research_id', 'research.id')->get();
                    
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 3)->exists() ) {
                            if( 
                                Report::join('research_publications', 'research_publications.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 3)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_presentations', 'research_presentations.research_id', 'research.id')
                                    ->where('research.college_id', $collegeID)->get();
                            
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 4)->exists() ) {
                            if ( 
                                Report::join('research_presentations', 'research_presentations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 4)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                            ->join('research', 'research.id', 'research_citations.research_id')->where('research.user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'research.college_id')  
                            ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                            ->where('research.college_id', $collegeID)->get();

                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 5)->exists() ) {
                            if ( 
                                Report::join('research_citations', 'research_citations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 5)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                                ->join('research', 'research.id', 'research_utilizations.research_id')->where('research.user_id', auth()->id())
                                                ->join('colleges', 'colleges.id', 'research.college_id')  
                                                ->join('dropdown_options', 'dropdown_options.id', 'research.classification')
                                                ->where('research.college_id', $collegeID)->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 6)->exists() ) {
                            if ( 
                                Report::join('research_utilizations', 'research_utilizations.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 6)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                                    ->join('research_copyrights', 'research_copyrights.research_id', 'research.id')
                                    ->where('research.college_id', $collegeID)->get();
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 7)->exists() ) {
                            if ( 
                                Report::join('research_copyrights', 'research_copyrights.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 7)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 8)->exists() ) {
                            if ( 
                                Report::join('inventions', 'inventions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 8)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 9)->exists() ) {
                            if ( 
                                Report::join('expert_service_consultants', 'expert_service_consultants.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 9)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 10)->exists() ) {
                            if ( 
                                Report::join('expert_service_conferences', 'expert_service_conferences.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 10)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 11)->exists() ) {
                            if ( 
                                Report::join('expert_service_academics', 'expert_service_academics.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                                            ->where('extension_services.college_id', $collegeID)
                                            ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 12)->exists() ) {
                            if ( 
                                Report::join('extension_services', 'extension_services.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 12)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                    if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 13)->exists() ) {
                        if ( 
                            Report::join('partnerships', 'partnerships.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                ->where('reports.report_category_id', 13)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 14)->exists() ) {
                            if ( 
                                Report::join('mobilities', 'mobilities.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 14)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 15)->exists() ) {
                            if ( 
                                Report::join('references', 'references.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                                    ->where('syllabi.college_id', $collegeID)

                                    ->get();
                    $tempdata = [];
                    foreach($data as $row){
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 16)->exists() ) {
                            if ( 
                                Report::join('syllabi', 'syllabi.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 16)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 17)->exists() ) {
                            if ( 
                                Report::join('requests', 'requests.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 17)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 18)->exists() ) {
                            if ( 
                                Report::join('student_awards', 'student_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 19)->exists() ) {
                            if ( 
                                Report::join('student_trainings', 'student_trainings.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 19)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 20)->exists() ) {
                            if ( 
                                Report::join('viable_projects', 'viable_projects.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 20)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 21)->exists() ) {
                            if ( 
                                Report::join('college_department_awards', 'college_department_awards.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 21)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 22)->exists() ) {
                            if ( 
                                Report::join('outreach_programs', 'outreach_programs.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 22)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
                        if ( Report::where('report_reference_id', $row->id)->where('report_category_id', 23)->exists() ) {
                            if ( 
                                Report::join('technical_extensions', 'technical_extensions.id', 'reports.report_reference_id')->where('reports.report_reference_id', $row->id)
                                    ->where('reports.report_category_id', 23)->where('reports.created_at', '<=', $row->updated_at)->exists()
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
            }
            
        }

        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
        }
        // dd($department_id);
        if(in_array(6, $roles)){
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
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
        return view('submissions.index', compact('report_tables', 'report_array' , 'report_document_checker', 'roles', 'department_id', 'college_id', 'colleges', 'collegeID'));
    }
}
