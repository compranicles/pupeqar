<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    CollegeDepartmentAwardDocument,
    CommunityEngagementDocument,
    DenyReason,
    ExpertServiceAcademicDocument,
    ExpertServiceConferenceDocument,
    ExpertServiceConsultantDocument,
    ExtensionServiceDocument,
    IntraMobilityDocument,
    InventionDocument,
    MobilityDocument,
    OtherAccomplishmentDocument,
    OtherDeptAccomplishmentDocument,
    OutreachProgramDocument,
    PartnershipDocument,
    ReferenceDocument,
    Report,
    RequestDocument,
    Research,
    ResearchCitation,
    ResearchComplete,
    ResearchCopyright,
    ResearchDocument,
    ResearchPublication,
    ResearchPresentation,
    ResearchUtilization,
    StudentAwardDocument,
    SyllabusDocument,
    StudentTrainingDocument,
    TechnicalExtensionDocument,
    ViableProjectDocument,
    FormBuilder\DropdownOption,
    Maintenance\Currency,
    Maintenance\HRISField,
    Maintenance\ReportCategory,
    Maintenance\ReportColumn,
    AdminSpecialTaskDocument,
    SpecialTaskDocument,
    AttendanceFunctionDocument,
};

class ReportDataController extends Controller
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
            if($report_category_id == 1){
                $research_id = $id;
            }
            if($report_category_id == 2){
                $research_code = ResearchComplete::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            elseif($report_category_id == 3){
                $research_code = ResearchPublication::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            elseif($report_category_id == 4){
                $research_code = ResearchPresentation::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            elseif($report_category_id == 5){
                $research_code = ResearchCitation::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            elseif($report_category_id == 6){
                $research_code = ResearchUtilization::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            elseif($report_category_id == 7){
                $research_code = ResearchCopyright::where('id', $id)->pluck('research_code')->first();
                $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
            }
            foreach($report_columns as $column){
                if($column->table == 'research_citations'){
                    if (Schema::hasColumn('research_citations', $column->column))
                        $data = DB::table('research_citations')->where('id', $id)->value($column->column);
                    else
                        $data = null;
                }
                elseif($column->table == 'research_utilizations'){
                    if (Schema::hasColumn($column->table, $column->column))
                        $data = DB::table($column->table)->where('id', $id)->value($column->column);
                    else
                        $data = null;
                }
                elseif($column->table == 'research'){
                    if (Schema::hasColumn($column->table, $column->column))
                        $data = DB::table($column->table)->where('id', $research_id)->value($column->column);
                    else
                        $data = null;

                    if ($column->column == "start_date" || $column->column == "target_date" || $column->column == "completion_date" ) {
                        if($data == null)
                            $data = '-';
                        else{
                            $date = strtotime( $data );
                            $data = date( 'F d, Y', $date );
                        }
                    }
                }
                else {
                    if (Schema::hasColumn($column->table, $column->column))
                        $data = DB::table($column->table)->where('id', $id)->value($column->column);
                    else
                        $data = null;

                    if ($column->column == "completion_date" || $column->column == "publish_date" || $column->column == "date_presented") {
                        if($data == null)
                            $data = '-';
                        else{
                            $date = strtotime( $data );
                            $data = date( 'F d, Y', $date );
                        }
                    }
                }


                if(is_int($data)){
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
                            $column->column == 'year' ||
                            $column->column == 'rate_of_return' ||
                            $column->column == 'has_businesses'
                        )
                            $data = $data;
                        else{
                            $data = DropdownOption::where('id', $data)->pluck('name')->first();
                        }

                    }
                }
                if($column->column == 'college_id'){
                    $data = DB::table('research')->where($column->table.'.id', $research_id)
                        ->join('colleges', 'colleges.id', $column->table.'.college_id')
                        ->pluck('colleges.name')->first();
                }
                if($column->column == 'department_id'){
                    $data = DB::table('research')->where($column->table.'.id', $research_id)
                        ->join('departments', 'departments.id', $column->table.'.department_id')
                        ->pluck('departments.name')->first();
                }
                if($column->column == 'funding_amount'){
                    if ($column->table == 'research') {
                        $curr = DB::table($column->table)->where('id', $research_id)->value('currency_funding_amount');
                    }
                    else {
                        $curr = DB::table($column->table)->where('id', $research_id)->value('currency_funding_amount');
                    }
                    $currName = Currency::where('id', $curr)->pluck('code')->first();
                    $data = number_format($data, 2, '.', ',');
                    $data = $currName.' '.$data;
                }
                if($column->column == 'researchers'){
                    $data = DB::table($column->table)->where('id', $research_id)->pluck($column->column)->first();
                }

                array_push($report_data_array, $data);
            }
        }
        else{
            if($report_category_id >= 8){
                foreach($report_columns as $column){
                    if (Schema::hasColumn($column->table, $column->column))
                        $data = DB::table($column->table)->where('id', $id)->value($column->column);
                    else
                        $data = null;

                    if ($column->column == "start_date" || $column->column == "end_date" || $column->column == "issue_date"
                        || $column->column == "from" || $column->column == "to" || $column->column == "date"
                        || $column->column == "date_started" || $column->column == "date_completed" || $column->column == "date_published"
                        || $column->column == "date_finished" || $column->column == 'target_date' || $column->column == 'actual_date') {
                        if($data == null)
                            $data = '-';
                        else{
                            $date = strtotime( $data );
                        $data = date( 'F d, Y', $date );
                        }
                    }

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
                            $column->column =='year' ||
                            $column->column == 'rate_of_return' ||
                            $column->column == 'has_businesses' ||
                            $column->column == 'no_of_students' ||
                            $column->column == 'total_hours'
                        )
                            $data = $data;
                        else{
                            $data = DropdownOption::where('id', $data)->pluck('name')->first();
                        }

                    }
                    // if($column->column == 'partnership_type')
                    //     $data = DropdownOption::where('id', $data)->pluck('name')->first();
                    if($column->column == 'funding_amount'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_funding_amount');
                        $currName = Currency::where('id', $curr)->pluck('code')->first();
                        $data = number_format($data, 2, '.', ',');
                        $data = $currName.' '.$data;
                    }
                    if($column->column == 'total_profit'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_total_profit');
                        $currName = Currency::where('id', $curr)->pluck('code')->first();
                        $data = number_format($data, 2, '.', ',');
                        $data = $currName.' '.$data;
                    }
                    if($column->column == 'revenue'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_revenue');
                        $currName = Currency::where('id', $curr)->pluck('code')->first();
                        $data = number_format($data, 2, '.', ',');
                        $data = $currName.' '.$data;
                    }
                    if($column->column == 'cost'){
                        $curr = DB::table($column->table)->where('id', $id)->value('currency_cost');
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
                    if($column->column == 'college_id'){
                        $data = DB::table($column->table)->where($column->table.'.id', $id)
                            ->join('colleges', 'colleges.id', $column->table.'.college_id')
                            ->pluck('colleges.name')->first();

                    }
                    if($column->column == 'department_id'){
                        $data = DB::table($column->table)->where($column->table.'.id', $id)
                            ->join('departments', 'departments.id', $column->table.'.department_id')
                            ->pluck('departments.name')->first();
                    }
                    array_push($report_data_array, $data);
                }
            }
        }

        if ($column)
        return $report_data_array;
    }

    public function getDocuments($report_category_id, $id){
        $report_docs;

        if($report_category_id == 1){
            $research_code = Research::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 1)->pluck('filename')->all();
        }
        elseif($report_category_id == 2){
            $research_code = ResearchComplete::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 2)->pluck('filename')->all();
        }
        elseif($report_category_id == 3){
            $research_code = ResearchPublication::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 3)->pluck('filename')->all();
        }
        elseif($report_category_id == 4){
            $research_code = ResearchPresentation::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 4)->pluck('filename')->all();
        }
        elseif($report_category_id == 5){
            $research_code = ResearchCitation::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 5)->where('research_citation_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 6){
            $research_code = ResearchUtilization::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 6)->where('research_utilization_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 7){
            $research_code = ResearchCopyright::where('id', $id)->pluck('research_code')->first();
            $report_docs = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 7)->pluck('filename')->all();
        }
        elseif($report_category_id == 8){
            $report_docs = InventionDocument::where('invention_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 9){
            $report_docs = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 10){
            $report_docs = ExpertServiceConferenceDocument::where('expert_service_conference_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 11){
            $report_docs = ExpertServiceAcademicDocument::where('expert_service_academic_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 12){
            $ext_code = ExtensionServiceDocument::where('extension_service_id', $id)->pluck('ext_code')->first();
            $report_docs = ExtensionServiceDocument::where('ext_code', $ext_code)->pluck('filename')->all();
        }
        elseif($report_category_id == 13){
            $report_docs = PartnershipDocument::where('partnership_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 14){
            $report_docs = MobilityDocument::where('mobility_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 15){
            $report_docs = ReferenceDocument::where('reference_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 16){
            $report_docs = SyllabusDocument::where('syllabus_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 17){
            $report_docs = RequestDocument::where('request_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 18){
            $report_docs = StudentAwardDocument::where('student_award_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 19){
            $report_docs = StudentTrainingDocument::where('student_training_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 20){
            $report_docs = ViableProjectDocument::where('viable_project_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 21){
            $report_docs = CollegeDepartmentAwardDocument::where('college_department_award_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 22){
            $report_docs = OutreachProgramDocument::where('outreach_program_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 23){
            $report_docs = TechnicalExtensionDocument::where('technical_extension_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 29){
            $report_docs = AdminSpecialTaskDocument::where('special_task_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 30){
            $report_docs = SpecialTaskDocument::where('special_task_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 31){
            $report_docs = SpecialTaskDocument::where('special_task_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 32){
            $report_docs = SpecialTaskDocument::where('special_task_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 33){
            $report_docs = AttendanceFunctionDocument::where('attendance_function_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 34){
            $report_docs = IntraMobilityDocument::where('intra_mobility_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 35){
            $report_docs = MobilityDocument::where('mobility_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 36){
            $report_docs = IntraMobilityDocument::where('intra_mobility_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 37){
            $report_docs = CommunityEngagementDocument::where('community_engagement_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 38){
            $report_docs = OtherAccomplishmentDocument::where('other_accomplishment_id', $id)->pluck('filename')->all();
        }
        elseif($report_category_id == 39){
            $report_docs = OtherDeptAccomplishmentDocument::where('other_dept_accomplishment_id', $id)->pluck('filename')->all();
        }
        return $report_docs;

    }

    public function getReportCategory($reportID) {
        $category = Report::where('reports.id', $reportID)->join('report_categories', 'reports.report_category_id',
                'report_categories.id')->pluck('report_categories.name')->first();
        return $category;
    }

    public function getReportData($report_id){
        $report_data = Report::where('id', $report_id)->first();
        $report_details = json_decode($report_data->report_details, true);
        $new_report_details = [];
        $report_columns;

        if($report_data->report_category_id <= '23' || $report_data->report_category_id >= '29'){
            $report_columns = ReportColumn::where('report_category_id', $report_data->report_category_id)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if(isset($report_details[$row->column]))
                    $new_report_details[$row->name] = $report_details[$row->column];
                else
                    $new_report_details[$row->name] = '-';
            }
        }
        elseif($report_data->report_category_id == '24'){
            $report_columns = HRISField::where('h_r_i_s_form_id', 1)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if($row->name == 'document')
                    continue;
                if(isset($report_details[$row->column]))
                    $new_report_details[$row->label] = $report_details[$row->column];
                else
                    $new_report_details[$row->label] = '-';
            }
        }
        elseif($report_data->report_category_id == '27'){
            $report_columns = HRISField::where('h_r_i_s_form_id', 2)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if($row->name == 'document')
                    continue;
                if(isset($report_details[$row->column]))
                    $new_report_details[$row->label] = $report_details[$row->column];
                else
                    $new_report_details[$row->label] = '-';
            }
        }
        elseif($report_data->report_category_id == '28'){
            $report_columns = HRISField::where('h_r_i_s_form_id', 3)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if($row->name == 'document')
                    continue;
                if(isset($report_details[$row->name]))
                    $new_report_details[$row->label] = $report_details[$row->name];
                else
                    $new_report_details[$row->label] = '-';
            }
        }
        elseif($report_data->report_category_id == '25'){
            $report_columns = HRISField::where('h_r_i_s_form_id', 4)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if($row->name == 'document')
                    continue;
                if(isset($report_details[$row->name]))
                    $new_report_details[$row->label] = $report_details[$row->name];
                else
                    $new_report_details[$row->label] = '-';
            }
        }
        elseif($report_data->report_category_id == '26'){
            $report_columns = HRISField::where('h_r_i_s_form_id', 5)->where('is_active', 1)->orderBy('order')->get();
            foreach($report_columns as $row){
                if($row->name == 'document')
                    continue;
                if(isset($report_details[$row->name]))
                    $new_report_details[$row->label] = $report_details[$row->name];
                else
                    $new_report_details[$row->label] = '-';
            }
        }

        return $new_report_details;
    }

    public function getDocumentsUsingId($report_id){
        $report_docs = json_decode(Report::where('id', $report_id)->pluck('report_documents')->first(), true);

        return $report_docs;
    }

    public function getRejectDetails($report_id){
        $deny_details = DenyReason::where('report_id', $report_id)->latest()->first();
        $newtime = strtotime($deny_details['created_at']);
        $deny_details->time = date("F j, Y, g:i a", $newtime);
        return json_decode($deny_details);
    }

    public function viewReportOrigin($report_id, $report_category_id){
        switch($report_category_id){
            case 1:
                $id = Report::where('id', $report_id)->pluck('report_reference_id')->all();
                return redirect()->route('research.edit', $id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 2:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.completed.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 3:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.publication.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 4:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.presentation.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 5:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.citation.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 6:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.utilization.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 7:
                $report = Report::where('id', $report_id)->first();
                $research_id = Research::where('research_code', $report->report_code)->where('user_id', $report->user_id)->pluck('id')->first();
                return redirect()->route('research.copyrighted.edit', [$research_id, $report->report_reference_id])->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 8:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('invention-innovation-creative.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 9:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('expert-service-as-consultant.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 10:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('expert-service-in-conference.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 11:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('expert-service-in-academic.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 12:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('extension-service.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 13:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('partnership.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 14:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('mobility.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 15:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('rtmmi.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 16:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('syllabus.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 17:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('request.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 18:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('student-award.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 19:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('student-training.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 20:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('viable-project.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 21:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('college-department-award.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 22:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('outreach-program.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 23:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('technical-extension.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 24:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('submissions.educ.add', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 25:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('submissions.development.seminar.add', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 26:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('submissions.development.training.add', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 27:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('submissions.award.add', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 28:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('submissions.officership.add', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 29:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('admin-special-tasks.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 30: case 31: case 32:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('special-tasks.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 33:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('attendance-function.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 34:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('intra-mobility.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 37:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('community-engagement.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 38:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('other-accomplishment.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
            case 39:
                $report = Report::where('id', $report_id)->first();
                return redirect()->route('other-dept-accomplishment.edit', $report->report_reference_id)->with('denied', DenyReason::where('report_id', $report_id)->first());
                break;
        }
    }
}
