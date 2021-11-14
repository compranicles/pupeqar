<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use Illuminate\Support\Facades\DB;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\ReportColumn;
use App\Models\FormBuilder\DropdownOption;

class ReportController extends Controller
{
    public function getColumnDataPerReportCategory($report_category_id){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        // dd($report_columns);
        return $report_columns;
    }

    public function getTableDataPerColumnCategory($report_category_id, $research_code){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        $report_data_array = [];
        $id;
        if($report_category_id == 5){
            $id = $research_code;
            $research_code = ResearchCitation::where('id', $id)->pluck('research_code')->first();
        }
        elseif($report_category_id == 6){
            $id = $research_code;
            $research_code = ResearchUtilization::where('id', $id)->pluck('research_code')->first();
        }
        foreach($report_columns as $column){
            if($column->table == 'research_citations'){
                $data = DB::table($column->table)->where('research_code', $research_code)->where('id', $id)->value($column->column);
            }
            elseif($column->table == 'research_utilizations'){
                $data = DB::table($column->table)->where('research_code', $research_code)->where('id', $id)->value($column->column);
            }
            else{
                $data = DB::table($column->table)->where('research_code', $research_code)->value($column->column);
            }
            if($data == null)
                $data = '-';
            if(is_int($data))
                $data = DropdownOption::where('id', $data)->pluck('name')->first();
            if($column->column == 'funding_amount'){
                $curr = DB::table($column->table)->where('research_code', $research_code)->value('currency');
                $currName = Currency::where('id', $curr)->pluck('code')->first();
                $data = number_format($data, 2, '.', ',');
                $data = $currName.' '.$data;
            }
            if($column->column == 'researchers'){
                $data = DB::table($column->table)->where('research_code', $research_code)->pluck($column->column)->first();
            } 

            array_push($report_data_array, $data);
        
        }
        
        return $report_data_array;
    }

    public function getDocuments($report_category_id, $research_code){
        $report_docs;
        if($report_category_id <= 4 || $report_category_id == 7){
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->pluck('filename')->all();
        }
        elseif($report_category_id == 5){
            $id = $research_code;
            $research_code = ResearchCitation::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->where('research_citation_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 6){
            $id = $research_code;
            $research_code = ResearchUtilization::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->where('research_utilization_id', $id)->pluck('filename')->all();
        }
        return $report_docs;
    }
}
