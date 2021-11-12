<?php

namespace App\Http\Controllers\Reports;

use App\Models\Research;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\ReportCategory;

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
                    $data = Research::select('research.research_code')->where('user_id', auth()->id())->orderBy('updated_at', 'desc')->get();
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
                            join('research_completes', 'research_completes.research_code', 'research.research_code')->get();
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
                    $data = ResearchCitation::select('research_citations.id','research.research_code')->
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
                    $data = ResearchUtilization::select('research_utilizations.id','research.research_code')->
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
        // dd($report_document_checker);
        return view('reports.faculty.index', compact('report_tables', 'report_array' , 'report_document_checker'));
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
        //
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
