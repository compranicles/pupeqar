<?php

namespace App\Http\Controllers;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Mobility;
use App\Models\Research;
use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\SectorHead;
use App\Models\Chairperson;
use App\Models\Partnership;
use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\ViableProject;
use App\Models\OutreachProgram;
use App\Models\StudentTraining;
use App\Models\ExtensionService;
use App\Models\TechnicalExtension;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Models\ExpertServiceAcademic;
use App\Models\CollegeDepartmentAward;
use App\Models\Authentication\UserRole;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use App\Models\Request as RequestModel;

class DashboardController extends Controller
{
    public function index() {
        
        $currentQuarterYear = Quarter::find(1);

        $totalReports = Report::whereMonth('report_date', '<=', 3)
           ->where('user_id', auth()->id())
           ->where('report_quarter', $currentQuarterYear->current_quarter)
           ->where('report_year', $currentQuarterYear->current_year)
           ->count();
        $department_reported = '';
        $cbco_reported  = '';
        $departments = '';
        $chairpersonReceived = '';
        $chairpersonNotReceived = '';
        $colleges = '';
        $deanReceived = '';
        $deanNotReceived = '';
        $sectors = '';
        $vpReceived = '';
        $vpNotReceived = '';
        $ipqmsoReceived = '';
        $ipqmsoReturned = '';
        $ipqmsoNotReceived = '';

        //No. of accomplishments received by Chairperson
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        if (in_array(1, $roles) || in_array(2, $roles) || in_array(3, $roles) || in_array(4, $roles)) {
            $department_reported = Report::where('user_id', auth()->id())->distinct('department_id')->count();
            $cbco_reported = Report::where('user_id', auth()->id())->distinct('college_id')->count();
        }
        if (in_array(5, $roles)) {
            $departments = Chairperson::where('chairpeople.user_id', auth()->id())
                ->join('departments', 'departments.id', 'chairpeople.department_id')
                ->pluck('chairpeople.department_id')->all();
            $chairpersonReceived = Report::where('reports.chairperson_approval', 1)
                ->whereIn('reports.department_id', [$departments])
                ->where('user_id', auth()->id())
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $chairpersonNotReceived = Report::where('reports.chairperson_approval', null)
                ->whereIn('reports.department_id', [$departments])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                //check for researcher and extensionist                   
                ->where('reports.researcher_approval', 1)
                ->orWhere('reports.extensionist_approval', 1)
                ->count();
                // $chairpersonReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
                //         ->where('chairperson_approval', 0)
                //         ->whereIn('department_id', [$departments])
                //         ->whereYear('report_date', date('Y'))->count();
                // $deanReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
                //         ->where('dean_approval', 0)
                //         ->where('user_id', auth()->id())
                //         ->whereYear('report_date', date('Y'))->count();
        }
        if (in_array(6, $roles)) {
            $colleges = Dean::where('deans.user_id', auth()->id())
                        ->join('colleges', 'colleges.id', 'deans.college_id')->pluck('deans.college_id')->all();
                        // dd($colleges);
            $deanReceived = Report::where('dean_approval', 1)
                ->whereIn('college_id', [$colleges])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $deanNotReceived = Report::where('chairperson_approval', 1)
                ->where('dean_approval', null)
                ->whereIn('college_id', [$colleges])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            // $deanReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
            //         ->where('dean_approval', 0)
            //         ->whereIn('college_id', [$colleges])
            //         ->whereYear('report_date', date('Y'))->count();
            // $sectorReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
            //         ->where('sector_approval', 0)
            //         ->where('user_id', auth()->id())
            //         ->whereYear('report_date', date('Y'))->count();

        }
        if (in_array(7, $roles)) {
            $sectors = SectorHead::where('sector_heads.user_id', auth()->id())
                ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->pluck('sector_heads.sector_id')->all();
            $vpReceived = Report::where('reports.sector_approval', 1)
                ->whereIn('reports.sector_id', [$sectors])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $vpNotReceived = Report::where('chairperson_approval', 1)
                ->where('dean_approval', 1)
                ->where('sector_approval', null)
                ->whereIn('sector_id', [$sectors])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            // $vpReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
            //         ->where('sector_approval', 0)
            //         ->whereIn('sector_id', [$sectors])
            //         ->whereYear('report_date', date('Y'))->count();
            // $ipqmsoReturned = Report::where(DB::raw('QUARTER(reports.report_date)'), $quarter)
            //         ->where('ipqmso_approval', 0)
            //         ->where('user_id', auth()->id())
            //         ->whereYear('report_date', date('Y'))->count();

        }
        if (in_array(8, $roles)) {
            $ipqmsoReceived = Report::where('ipqmso_approval', 1)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $ipqmsoNotReceived = Report::where('ipqmso_approval', null)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
            $ipqmsoReturned = Report::where('ipqmso_approval', 0)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        }
        return view('dashboard', 
                        compact(
                                    'totalReports', 
                                    'currentQuarterYear', 
                                    'department_reported', 
                                    'cbco_reported', 
                                    'currentQuarterYear',
                                    'chairpersonReceived', 
                                    'chairpersonNotReceived',
                                    'deanReceived', 
                                    'deanNotReceived',
                                    'vpReceived', 
                                    'vpNotReceived', 
                                    'ipqmsoReceived', 
                                    'ipqmsoNotReceived', 
                                    'ipqmsoReturned',
                            ));
    }
}
