<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\ReportColumn;
use App\Models\FormBuilder\DropdownOption;

class ReportController extends Controller
{
    public function getColumnDataPerReportCategory($report_category_id){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        return $report_columns;
    }

    public function getTableDataPerColumnCategory($report_category_id, $research_code){
        $report_columns = ReportColumn::where('report_category_id', $report_category_id)->where('is_active', 1)->orderBy('order')->get();
        $report_data_array = [];

        foreach($report_columns as $column){
            $data = DB::table($column->table)->where('research_code', $research_code)->value($column->column);
            if(is_int($data))
                $data = DropdownOption::where('id', $data)->pluck('name')->first();
            if($column->column == 'funding_amount'){
                $curr = DB::table($column->table)->where('research_code', $research_code)->value('currency');
                $currName = Currency::where('id', $curr)->pluck('code')->first();
                $data = number_format($data, 2, '.', ',');
                $data = $currName.' '.$data;
            }

            array_push($report_data_array, $data);
        }
        return $report_data_array;
    }
}
