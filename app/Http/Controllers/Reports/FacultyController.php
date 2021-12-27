<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use App\Models\Mobility;
use App\Models\Research;
use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\Partnership;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExtensionService;
use App\Models\MobilityDocument;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use App\Models\SyllabusDocument;
use App\Models\InventionDocument;
use App\Models\ReferenceDocument;
use App\Models\PartnershipDocument;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\ExpertServiceAcademic;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use Illuminate\Support\Facades\Storage;
use App\Models\ExtensionServiceDocument;
use App\Models\Maintenance\ReportCategory;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\ExpertServiceConferenceDocument;
use App\Models\ExpertServiceConsultantDocument;
use App\Http\Controllers\Reports\ReportController;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $report_tables = ReportCategory::all();
        $report_array = [];
        $report_document_checker = [];
        $checker_array = [];
        foreach($report_tables as $table){
            switch($table->id){
                case '1':
                    $data = Research::select('research.id', 'research.research_code')->where('user_id', auth()->id())
                            ->whereNotIn('research.research_code', Report::where('report_category_id', 1)->where('user_id', auth()->id())->pluck('report_code')->all() )->orderBy('updated_at', 'desc')->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->research_code] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
        
                    break;
                case '2':
                    $data = Research::select('research_completes.id', 'research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 2)->where('user_id', auth()->id())->pluck('report_code')->all() )
                            ->join('research_completes', 'research_completes.research_code', 'research.research_code')->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->research_code] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '3':
                    $data = Research::select('research_publications.id','research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 3)->where('user_id', auth()->id())->pluck('report_code')->all() )->
                            join('research_publications', 'research_publications.research_code', 'research.research_code')->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->research_code] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '4':
                    $data = Research::select('research_presentations.id','research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 4)->where('user_id', auth()->id())->pluck('report_code')->all() )->
                            join('research_presentations', 'research_presentations.research_code', 'research.research_code')->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->research_code] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '5':
                    $data = ResearchCitation::select('research_citations.id','research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 5)->where('user_id', auth()->id())->pluck('report_code')->all() )->
                            orWhereNotIn('research_citations.id', Report::where('report_category_id', 5)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->
                            join('research', 'research.research_code', 'research_citations.research_code')->where('research.user_id', auth()->id())->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->
                                where('research_citation_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '6':
                    $data = ResearchUtilization::select('research_utilizations.id','research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 6)->where('user_id', auth()->id())->pluck('report_code')->all() )->
                            orWhereNotIn('research_utilizations.id', Report::where('report_category_id', 6)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->
                            join('research', 'research.research_code', 'research_utilizations.research_code')->where('research.user_id', auth()->id())->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->
                                where('research_utilization_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '7':
                    $data = Research::select('research_copyrights.id', 'research.research_code')->where('user_id', auth()->id())->
                            whereNotIn('research.research_code', Report::where('report_category_id', 7)->where('user_id', auth()->id())->pluck('report_code')->all() )->
                            join('research_copyrights', 'research_copyrights.research_code', 'research.research_code')->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = ResearchDocument::where('research_code', $row->research_code)->where('research_form_id', $table->id)->get();
                            $checker_array[$row->research_code] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '8': 
                    $data = Invention::select('inventions.*')->where('user_id', auth()->id())->
                            whereNotIn('inventions.id', Report::where('report_category_id', 8)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = ExpertServiceConsultant::select('expert_service_consultants.*')->where('user_id', auth()->id())->
                            whereNotIn('expert_service_consultants.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = ExpertServiceConference::select('expert_service_conferences.*')->where('user_id', auth()->id())->
                            whereNotIn('expert_service_conferences.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = ExpertServiceAcademic::select('expert_service_academics.*')->where('user_id', auth()->id())->
                            whereNotIn('expert_service_academics.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = ExtensionService::select('extension_services.*')->where('user_id', auth()->id())->
                            whereNotIn('extension_services.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = Partnership::select('partnerships.*')->where('user_id', auth()->id())->
                            whereNotIn('partnerships.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = Mobility::select('mobilities.*')->where('user_id', auth()->id())->
                            whereNotIn('mobilities.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = Reference::select('references.*')->where('user_id', auth()->id())->
                            whereNotIn('references.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = Syllabus::select('syllabi.*')->where('user_id', auth()->id())->
                            whereNotIn('syllabi.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = Syllabus::select('requests.*')->where('user_id', auth()->id())->
                            whereNotIn('requests.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = SyllabusDocument::where('request_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '18': 
                        $data = StudentAward::select('student_awards.*')->where('user_id', auth()->id())->
                                whereNotIn('student_awards.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = StudentTraining::select('student_trainings.*')->where('user_id', auth()->id())->
                            whereNotIn('student_trainings.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = ViableProject::select('viable_projects.*')->where('user_id', auth()->id())->
                            whereNotIn('viable_projects.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = CollegeDepartmentAward::select('college_department_awards.*')->where('user_id', auth()->id())->
                            whereNotIn('college_department_awards.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
                    if($data != null){
                        foreach($data as $row){
                            $checker = CollegeDepartmentAwardDocument::where('viable_project_id', $row->id)->get();
                            $checker_array[$row->id] = $checker;
                        }
                    }
                    $report_array[$table->id] = $data;
                    $report_document_checker[$table->id] = $checker_array;
                    $checker_array = [];
                    break;
                case '22': 
                    $data = OutreachProgram::select('outreach_programs.*')->where('user_id', auth()->id())->
                            whereNotIn('outreach_programs.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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
                    $data = TechnicalExtension::select('technical_extensions.*')->where('user_id', auth()->id())->
                            whereNotIn('technical_extensions.id', Report::where('report_category_id', $table->id)->where('user_id', auth()->id())->pluck('report_reference_id')->all() )->get();
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

        $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->orWhere('reports.dean_approval', 0)
                    ->orWhere('reports.sector_approval', 0)->orWhere('reports.ipqmso_approval', 0)->get();
        // dd($report_array);
        // dd($report_document_checker);
        // dd($reported_accomplishments);
        return view('reports.faculty.index', compact('report_tables', 'report_array' , 'report_document_checker', 'reported_accomplishments'));
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
            foreach($request->report_values as $report_value){
                $report_values_array = explode(',', $report_value); // 0 => research_code , 1 => report_category, 2 => id
                switch($report_values_array[1]){
                    case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                        $collegeAndDepartment = Research::select('college_id', 'department_id')->where('user_id', $user_id)->where('research_code', $report_values_array[0])->first();
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
                            $reportValues = collect($report_controller->getTableDataPerColumnCategory($report_values_array[1], $report_values_array[0]));
                            $report_documents = $report_controller->getDocuments($report_values_array[1], $report_values_array[0]);
                        }
                        $report_details = array_combine($reportColumns->pluck('column')->toArray(), $reportValues->toArray());
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
                    break;
                    case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16:
                        switch($report_values_array[1]){
                            case 8:
                                $collegeAndDepartment = Invention::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            break;
                            // case 9:
                            //     $collegeAndDepartment = ExpertServiceConsultant::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            // break;
                            // case 10:
                            //     $collegeAndDepartment = ExpertServiceConference::select('college_id', 'department_id')->where('user_id', $user_id)->where('id', $report_values_array[2])->first();
                            // break;
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
                        Report::create([
                            'user_id' =>  $user_id,
                            'college_id' => $collegeAndDepartment->college_id ?? null,
                            'department_id' => $collegeAndDepartment->department_id ?? null,
                            'report_category_id' => $report_values_array[1],
                            'report_code' => $report_values_array[0] ?? null,
                            'report_reference_id' => $report_values_array[2] ?? null,
                            'report_details' => json_encode($report_details),
                            'report_documents' => json_encode($report_documents),
                            'report_date' => date("Y-m-d", time()),
                        ]);
                    break;
                }
                
            }
        }
        return redirect()->route('faculty.index')->with('success', 'Reports submitted successfully');
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

        return redirect()->route('faculty.index')->with('success', 'Document added successfully');
    }
}
