<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use App\Models\Research;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\ReportCategory;
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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())
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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())->
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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())->
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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())->
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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())->
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
            }
            
        }

        $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->orWhere('reports.dean_approval', 0)
                    ->orWhere('reports.sector_approval', 0)->orWhere('reports.ipqmso_approval', 0)->get();
    
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
}
