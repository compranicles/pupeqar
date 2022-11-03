<?php

namespace App\Http\Controllers\Reports\ToReceive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\{
    Associate,
    Chairperson,
    Dean,
    DenyReason,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
    SectorHead,
    User,
    Authentication\UserRole,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    Maintenance\ReportCategory,
};
use App\Notifications\ReceiveNotification;
use App\Notifications\ReturnNotification;
use App\Services\ToReceiveReportAuthorizationService;

class ResearcherController extends Controller
{
    public function index(){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        // dd($authorize);
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
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
            $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'faculty_researchers.cluster_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.college_id', 'colleges.code')
                                        ->join('colleges', 'colleges.id', 'faculty_extensionists.college_id')->get();
        }
        if(in_array(12, $roles)){
            $colleges = Associate::where('associates.user_id', auth()->id())->select('associates.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'associates.college_id')->get();
        }
        if(in_array(13, $roles)){
            $sectors = Associate::where('associates.user_id', auth()->id())->select('associates.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'associates.sector_id')->get();
        }

        $reportsToReview = collect();

        $currentQuarterYear = Quarter::find(1);

        foreach ($departmentsResearch as $row){
            $tempReports = Report::where('reports.report_year', $currentQuarterYear->current_year)
                ->where('reports.report_quarter', $currentQuarterYear->current_quarter)
                ->where('reports.format', 'f')
                ->whereIn('reports.report_category_id', [1, 2, 3, 4, 5, 6, 7])
                ->where('reports.research_cluster_id', $row->cluster_id)->where('researcher_approval', null)
                ->select('reports.*', 'dropdown_options.name as college_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('dropdown_options', 'reports.research_cluster_id', 'dropdown_options.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->orderBy('reports.created_at', 'DESC')
                ->get();

            $reportsToReview = $reportsToReview->concat($tempReports);
        }

        $college_names = [];
        $department_names = [];
        foreach($reportsToReview as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();
            $row->report_details = json_decode($row->report_details, false);


            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
                $department_names[$row->id] = $temp_department_name;
        }

        return view('reports.to-receive.researchers.index', compact('reportsToReview', 'roles', 'departments', 'colleges', 'college_names', 'department_names', 'sectors', 'departmentsResearch','departmentsExtension'));
    }

    public function accept($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['researcher_approval' => 1, 'chairperson_approval' => 1]);

        $report = Report::find($report_id);

        $receiverData = User::find($report->user_id);
        $senderName = FacultyResearcher::join('colleges', 'colleges.id', 'faculty_researchers.college_id')
                            ->join('users', 'users.id', 'faculty_researchers.user_id')
                            ->where('faculty_researchers.college_id', $report->college_id)
                            ->select('colleges.name as college_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('reports.consolidate.myaccomplishments');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_name.' Researcher)',
            'receiver' => $receiverData->first_name,
            'url' => $url,
            'category_name' => $report_category_name,
            'user_id' => $receiverData->id,
            'accomplishment_type' => 'individual',
            'date' => date('F j, Y, g:i a'),
            'databaseOnly' => 1
        ];

        Notification::send($receiverData, new ReceiveNotification($notificationData));

        \LogActivity::addToLog('Researcher received an accomplishment.');

        return redirect()->route('researcher.index')->with('success', 'Report has been added in college consolidation of reports');

    }
    public function rejectCreate($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.to-receive.researchers.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

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
        $senderName = FacultyResearcher::join('colleges', 'colleges.id', 'faculty_researchers.college_id')
                            ->join('users', 'users.id', 'faculty_researchers.user_id')
                            ->where('faculty_researchers.college_id', $report->college_id)
                            ->select('colleges.name as college_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('reports.consolidate.myaccomplishments');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_name.' Researcher)',
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

        \LogActivity::addToLog('Researcher returned an accomplishment.');

        return redirect()->route('researcher.index')->with('success', 'Report has been returned to the owner.');
    }

    public function acceptSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $count = 0;
        foreach($reportIds as $report_id){
            Report::where('id', $report_id)->update(['researcher_approval' => 1, 'chairperson_approval' => 1]);

            $report = Report::find($report_id);

            $receiverData = User::find($report->user_id);
            $senderName = FacultyResearcher::join('colleges', 'colleges.id', 'faculty_researchers.college_id')
                            ->join('users', 'users.id', 'faculty_researchers.user_id')
                            ->where('faculty_researchers.college_id', $report->college_id)
                            ->select('colleges.name as college_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('reports.consolidate.myaccomplishments');


            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_name.' Researcher)',
                'receiver' => $receiverData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $receiverData->id,
                'accomplishment_type' => 'individual',
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 1
            ];

            Notification::send($receiverData, new ReceiveNotification($notificationData));

            $count++;
        }

        \LogActivity::addToLog('Researcher received '.$count.' accomplishments.');

        return redirect()->route('researcher.index')->with('success', 'Report/s added in college consolidation of reports.');
    }

    public function denySelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');
        return view('reports.to-receive.researchers.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualResearch();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $count = 0;
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
            $senderName = FacultyResearcher::join('colleges', 'colleges.id', 'faculty_researchers.college_id')
                            ->join('users', 'users.id', 'faculty_researchers.user_id')
                            ->where('faculty_researchers.college_id', $report->college_id)
                            ->select('colleges.name as college_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')

                            ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('reports.consolidate.myaccomplishments');

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_name.' Researcher)',
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

            $count++;
        }

        \LogActivity::addToLog('Researcher returned '.$count.' accomplishments.');

        return redirect()->route('researcher.index')->with('success', 'Report/s returned to the owner/s.');

    }
}
