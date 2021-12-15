<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use App\Models\DenyReason;
use Illuminate\Http\Request;
use App\Models\MobilityDocument;
use App\Models\ResearchCitation;
use App\Models\ResearchDocument;
use App\Models\SyllabusDocument;
use App\Models\InventionDocument;
use App\Models\ReferenceDocument;
use Illuminate\Support\Facades\DB;
use App\Models\PartnershipDocument;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;
use App\Models\ExtensionServiceDocument;
use App\Models\Maintenance\ReportColumn;
use App\Models\FormBuilder\DropdownOption;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\ExpertServiceConferenceDocument;
use App\Models\ExpertServiceConsultantDocument;

class ReportController extends Controller
{
    public function getColumnDataPerReportCategory($report_category_id){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        // dd($report_columns);
        return $report_columns;
    }

    public function getTableDataPerColumnCategory($report_category_id, $id){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        $report_data_array = [];

        if($report_category_id <= 7){
            $research_code = $id;
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
                    $curr = DB::table($column->table)->where('research_code', $research_code)->value('currency_funding_amount');
                    $currName = Currency::where('id', $curr)->pluck('code')->first();
                    $data = number_format($data, 2, '.', ',');
                    $data = $currName.' '.$data;
                }
                if($column->column == 'researchers'){
                    $data = DB::table($column->table)->where('research_code', $research_code)->pluck($column->column)->first();
                } 
    
                array_push($report_data_array, $data);
            
            }
        }
        else{
            if($report_category_id >= 8){
                foreach($report_columns as $column){
                    $data = DB::table($column->table)->where('id', $id)->value($column->column);
                    if($data == null)
                        $data = '-';
                    if(is_int($data)){
                        if(   
                            $column->column == 'no_of_trainees_or_beneficiaries' || 
                            $column->column == 'quality_poor' || 
                            $column->column == 'quality_fair' ||
                            $column->column == 'quality_satisfactory' ||
                            $column->column == 'quality_very_satisfactory' ||
                            $column->column == 'quality_outstanding' ||
                            $column->column == 'timeliness_poor' || 
                            $column->column == 'timeliness_fair' ||
                            $column->column == 'timeliness_satisfactory' ||
                            $column->column == 'timeliness_very_satisfactory' ||
                            $column->column == 'timeliness_outstanding' ||
                            $column->column == 'volume' ||
                            $column->column == 'volume_no' ||
                            $column->column == 'issue' ||
                            $column->column == 'issue_no' ||
                            $column->column == 'page' ||
                            $column->column == 'page_no' ||
                            $column->column =='year' 
                        )
                            $data = $data;
                        else{
                            $data = DropdownOption::where('id', $data)->pluck('name')->first();
                        }

                    }
                    if($column->column == 'partnership_type')
                        $data = DropdownOption::where('id', $data)->pluck('name')->first();
                    if($column->column == 'funding_amount'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_funding_amount');
                        $currName = Currency::where('id', $curr)->pluck('code')->first();
                        $data = number_format($data, 2, '.', ',');
                        $data = $currName.' '.$data;
                    }
                    if($column->column == 'amount_of_funding'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_amount_of_funding');
                        $currName = Currency::where('id', $curr)->pluck('code')->first();
                        $data = number_format($data, 2, '.', ',');
                        $data = $currName.' '.$data;
                    }
                    array_push($report_data_array, $data);
                }
            }
        }
        
        
        return $report_data_array;
    }

    public function getDocuments($report_category_id, $id){
        $report_docs;
        if($report_category_id <= 4 || $report_category_id == 7){
            $research_code = $id;
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->pluck('filename')->all();
        }
        elseif($report_category_id == 5){
            $research_code = ResearchCitation::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->where('research_citation_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 6){
            $research_code = ResearchUtilization::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', $report_category_id)->where('research_utilization_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 8){
            $report_docs = InventionDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 9){
            $report_docs = ExpertServiceConsultantDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 10){
            $report_docs = ExpertServiceConferenceDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 11){
            $report_docs = ExpertServiceAcademicDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 12){
            $report_docs = ExtensionServiceDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 13){
            $report_docs = PartnershipDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 14){
            $report_docs = MobilityDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 15){
            $report_docs = ReferenceDocument::where('id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 16){
            $report_docs = SyllabusDocument::where('id', $id)->pluck('filename')->all();
        }
        return $report_docs;
    }

    public function getReportData($report_id){
        $report_data = Report::where('id', $report_id)->first();
        $report_details = json_decode($report_data->report_details, true);
        $report_columns = ReportColumn::where('report_category_id', $report_data->report_category_id)->where('is_active', 1)->orderBy('order')->get();

        $new_report_details = [];
        foreach($report_columns as $row){
            $new_report_details[$row->name] = $report_details[$row->column];
        }

        return $new_report_details;
    }

    public function getDocumentsUsingId($report_id){
        $report_docs = json_decode(Report::where('id', $report_id)->pluck('report_documents')->first(), true);

        return $report_docs;
    }

    public function getRejectDetails($report_id){
        $deny_details = DenyReason::where('report_id', $report_id)->first();
        $newtime = strtotime($deny_details->created_at);
        $deny_details->time = date("F j, Y, g:i a", $newtime);
        return $deny_details;
    }
}
