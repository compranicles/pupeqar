<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index(){
        $monthAndYear = [];
        $years = [];
        $quarters = [];

        $currentQuarterAndYear = Quarter::find(1);

        // For Seminar Hours
        // Seminar Hours Per Month
        $seminar_hours_per_month = [];

        for($i = 0; $i < 12; $i++){
            $seminar_hours_per_month[$i] = 0;
            $monthAndYear[$i] = date('m-Y', strtotime('-'.$i.' months'));
            $month = date('m', strtotime('-'.$i.' months'));
            $year = date('Y', strtotime('-'.$i.' months'));

            $seminars_per_month = Report::where('user_id', auth()->id())->
                where('report_category_id', '25')->whereMonth('updated_at', $month)->
                whereYear('updated_at', $year)->get();
            foreach($seminars_per_month as $seminar){
                $seminar_details = json_decode($seminar->report_details);
                $seminar_hours_per_month[$i] += $seminar_details->total_hours;
            }
        }

        // Seminar Hours Per Quarter
        $seminar_hours_per_quarter = [];
        $year = $currentQuarterAndYear->current_year;
        $quarter = $currentQuarterAndYear->current_quarter;

        for($i = 0; $i < 8; $i++){
            $seminar_hours_per_quarter[$i] = 0;
            $quarters[$i] = 'Q'.$quarter.'-'.$year;

            $seminars_per_quarter = Report::where('user_id', auth()->id())->
                where('report_category_id', '25')->where('report_quarter', $quarter)->
                where('report_year', $year)->get();
            foreach($seminars_per_quarter as $seminar){
                $seminar_details = json_decode($seminar->report_details);
                $seminar_hours_per_quarter[$i] += $seminar_details->total_hours;
            }
            $quarter = $quarter - 1;
            if($quarter == 0){
                $quarter = 4;
                $year = $year - 1;
            }
        }
        // Seminar Hours Per Year
        $seminar_hours_per_year = [];
        $year = $currentQuarterAndYear->current_year;

        for($i = 0; $i < 5; $i++){
            $seminar_hours_per_year[$i] = 0;
            $years[$i] = $year-$i;

            $seminars_per_year = Report::where('user_id', auth()->id())->
                where('report_category_id', '25')->where('report_year', $years[$i])->get();

            foreach($seminars_per_year as $seminar){
                $seminar_details = json_decode($seminar->report_details);
                $seminar_hours_per_year[$i] += $seminar_details->total_hours;
            }

        }
        // --------------------------------------------------------------------------------------------------------------------
        // For Training Hours
        // Training Hours Per Month
        $training_hours_per_month = [];

        for($i = 0; $i < 12; $i++){
            $training_hours_per_month[$i] = 0;
            $monthAndYear[$i] = date('m-Y', strtotime('-'.$i.' months'));
            $month = date('m', strtotime('-'.$i.' months'));
            $year = date('Y', strtotime('-'.$i.' months'));

            $trainings_per_month = Report::where('user_id', auth()->id())->
                where('report_category_id', '26')->whereMonth('updated_at', $month)->
                whereYear('updated_at', $year)->get();
            foreach($trainings_per_month as $training){
                $training_details = json_decode($training->report_details);
                $training_hours_per_month[$i] += $training_details->total_hours;
            }
        }

        // Training Hours Per Quarter
        $training_hours_per_quarter = [];
        $year = $currentQuarterAndYear->current_year;
        $quarter = $currentQuarterAndYear->current_quarter;

        for($i = 0; $i < 8; $i++){
            $training_hours_per_quarter[$i] = 0;
            $quarters[$i] = 'Q'.$quarter.'-'.$year;

            $trainings_per_quarter = Report::where('user_id', auth()->id())->
                where('report_category_id', '26')->where('report_quarter', $quarter)->
                where('report_year', $year)->get();
            foreach($trainings_per_quarter as $training){
                $training_details = json_decode($training->report_details);
                $training_hours_per_quarter[$i] += $training_details->total_hours;
            }
            $quarter = $quarter - 1;
            if($quarter == 0){
                $quarter = 4;
                $year = $year - 1;
            }
        }

        // Training Hours Per Year
        $training_hours_per_year = [];
        $year = $currentQuarterAndYear->current_year;

        for($i = 0; $i < 5; $i++){
            $training_hours_per_year[$i] = 0;
            $years[$i] = $year-$i;

            $trainings_per_year = Report::where('user_id', auth()->id())->
                where('report_category_id', '26')->where('report_year', $years[$i])->get();

            foreach($trainings_per_year as $training){
                $training_details = json_decode($training->report_details);
                $training_hours_per_year[$i] += $training_details->total_hours;
            }

        }

        // --------------------------------------------------------------------------------------------------------------------

        // Reverse all arrays to show most oldest first
        $monthAndYear = array_reverse($monthAndYear);
        $seminar_hours_per_month = array_reverse($seminar_hours_per_month);
        $training_hours_per_month = array_reverse($training_hours_per_month);
        $seminar_hours_per_quarter = array_reverse($seminar_hours_per_quarter);
        $training_hours_per_quarter = array_reverse($training_hours_per_quarter);
        $seminar_hours_per_year = array_reverse($seminar_hours_per_year);
        $training_hours_per_year = array_reverse($training_hours_per_year);
        $quarters = array_reverse($quarters);
        $years = array_reverse($years);

        return view('analytics.index', compact('monthAndYear', 'seminar_hours_per_month', 'seminar_hours_per_quarter', 'seminar_hours_per_year', 'training_hours_per_month', 'training_hours_per_quarter', 'training_hours_per_year', 'quarters', 'years'));
    }
}
