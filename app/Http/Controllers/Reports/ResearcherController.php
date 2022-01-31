<?php

namespace App\Http\Controllers\Reports;

use App\Models\Dean;
use App\Models\Report;
use App\Models\DenyReason;
use App\Models\SectorHead;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\FacultyResearcher;
use App\Models\FacultyExtensionist;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;

class ResearcherController extends Controller
{
    public function index(){
        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments = [];
        $colleges = [];
        $sectors = [];
        $departmentsResearch = [];
        $departmentsExtension = [];
        
        if(in_array(5, $roles)){
            $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'deans.college_id')->get();
        }
        if(in_array(7, $roles)){
            $sectors = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->get();
        }
        if(in_array(10, $roles)){
            $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }

        $reportsToReview = collect();
        $employees = collect();

        foreach ($departmentsResearch as $row){
            $tempReports = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->whereIn('reports.report_category_id', [1, 2, 3, 4, 5, 6, 7])
                ->where('department_id', $row->department_id)->where('researcher_approval', null)->get();

                        
            $tempEmployees = Report::join('users', 'reports.user_id', 'users.id')
                ->where('reports.department_id', $row->department_id)
                ->select('users.last_name', 'users.first_name', 'users.suffix', 'users.middle_name')
                ->whereIn('reports.report_category_id', [1, 2, 3, 4, 5, 6, 7])
                ->where('reports.researcher_approval', null)
                ->distinct()
                ->orderBy('users.last_name')
                ->get();
            
            $reportsToReview = $reportsToReview->concat($tempReports);
            $employees = $employees->concat($tempEmployees);
        }

        $college_names = [];
        $department_names = [];
        foreach($reportsToReview as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();


            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
                $department_names[$row->id] = $temp_department_name;
        }

        return view('reports.researchers.index', compact('reportsToReview', 'roles', 'departments', 'colleges', 'employees', 'college_names', 'department_names', 'sectors', 'departmentsResearch','departmentsExtension'));
    }

    public function accept($report_id){
        Report::where('id', $report_id)->update(['researcher_approval' => 1]);

        $report = Report::find($report_id);

        $receiverData = User::find($report->user_id);
        $senderName = Researchers::join('departments', 'researchers.id', 'researchers.department_id')
                            ->join('users', 'users.id', 'chairpeople.user_id')
                            ->where('researchers.department_id', $report->department_id)
                            ->select('departments.name as department_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('submissions.myaccomp.index');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_name.' Researcher)',
            'receiver' => $receiverData->first_name,
            'url' => $url,
            'category_name' => $report_category_name,
            'user_id' => $receiverData->id,
            'accomplishment_type' => 'individual',
            'date' => date('F j, Y, g:i a'),
            'databaseOnly' => 1
        ];

        Notification::send($receiverData, new ReceiveNotification($notificationData));

        return redirect()->route('researcher.index')->with('success', 'Report Accepted');
    
    }
    public function rejectCreate($report_id){
        return view('reports.researchers.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'researcher',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'researcher_approval' => 0
        ]);

         
        $report = Report::find($report_id);

        $returnData = User::find($report->user_id);
        $senderName = Researchers::join('departments', 'researchers.id', 'researchers.department_id')
                        ->join('users', 'users.id', 'chairpeople.user_id')
                        ->where('researchers.department_id', $report->department_id)
                        ->select('departments.name as department_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                        ->first();


        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('submissions.myaccomp.index');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_name.' Researcher)',
            'receiver' => $returnData->first_name,
            'url' => $url,
            'category_name' => $report_category_name,
            'user_id' => $returnData->id,
            'reason' => $request->input('reason'),
            'accomplishment_type' => 'individual',
            'date' => date('F j, Y, g:i a'),
            'databaseOnly' => 0
        ];

        Notification::send($returnData, new ReturnNotification($notificationData));

        return redirect()->route('researcher.index')->with('success', 'Report Denied');
    }

    public function acceptSelected(Request $request){
        $reportIds = $request->input('report_id');

        foreach($reportIds as $report_id){
            Report::where('id', $report_id)->update(['researcher_approval' => 1]);

            $report = Report::find($report_id);

            $receiverData = User::find($report->user_id);
            $senderName = Researchers::join('departments', 'researchers.id', 'researchers.department_id')
                                ->join('users', 'users.id', 'chairpeople.user_id')
                                ->where('researchers.department_id', $report->department_id)
                                ->select('departments.name as department_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('submissions.myaccomp.index');


            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_name.' Researcher)',
                'receiver' => $receiverData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $receiverData->id,
                'accomplishment_type' => 'individual',
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 1
            ];

            Notification::send($receiverData, new ReceiveNotification($notificationData));

        }
        return redirect()->route('researcher.index')->with('success', 'Report/s Approved Successfully');
    }

    public function denySelected(Request $request){
        $reportIds = $request->input('report_id');
        return view('reports.researchers.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $reportIds = $request->input('report_id');

        foreach($reportIds as $report_id){
            if($request->input('reason_'.$report_id) == null)
                continue;
            Report::where('id', $report_id)->update(['researcher_approval' => 0]);
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'researcher',
                'reason' => $request->input('reason_'.$report_id),
            ]);
            
            $report = Report::find($report_id);

            $returnData = User::find($report->user_id);
            $senderName = Researchers::join('departments', 'researchers.id', 'researchers.department_id')
                            ->join('users', 'users.id', 'chairpeople.user_id')
                            ->where('researchers.department_id', $report->department_id)
                            ->select('departments.name as department_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('submissions.myaccomp.index');

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_name.' Researcher)',
                'receiver' => $returnData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $returnData->id,
                'reason' => $request->input('reason'),
                'accomplishment_type' => 'individual',
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 0
            ];

            Notification::send($returnData, new ReturnNotification($notificationData));
        }
        return redirect()->route('researcher.index')->with('success', 'Report/s Denied Successfully');

    }
}
