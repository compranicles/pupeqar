<?php

namespace App\Http\Controllers\Reports\ToReceive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\{
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
    Maintenance\ReportCategory,
};
use App\Notifications\ReceiveNotification;
use App\Notifications\ReturnNotification;
use App\Services\ToReceiveReportAuthorizationService;

class ExtensionistController extends Controller
{
    public function index(){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
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
            $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
        }

        $reportsToReview = collect();

        foreach ($departmentsExtension as $row){
            $tempReports = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->whereIn('reports.report_category_id', [9, 10, 11, 12, 13, 14])
                ->where('department_id', $row->department_id)->where('extensionist_approval', null)->get();

            $reportsToReview = $reportsToReview->concat($tempReports);
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

        return view('reports.to-receive.extensionists.index', compact('reportsToReview', 'roles', 'departments', 'colleges', 'college_names', 'department_names', 'sectors', 'departmentsResearch','departmentsExtension'));
    }

    public function accept($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['extensionist_approval' => 1, 'chairperson_approval' => 1]);

        $report = Report::find($report_id);

        $receiverData = User::find($report->user_id);
        $senderName = FacultyExtensionist::join('departments', 'departments.id', 'faculty_extensionists.department_id')
                            ->join('users', 'users.id', 'faculty_extensionists.user_id')
                            ->where('faculty_extensionists.department_id', $report->department_id)
                            ->select('departments.code as department_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('reports.consolidate.myaccomplishments');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_code.' Extensionist)',
            'receiver' => $receiverData->first_name,
            'url' => $url,
            'category_name' => $report_category_name,
            'user_id' => $receiverData->id,
            'accomplishment_type' => 'individual',
            'date' => date('F j, Y, g:i a'),
            'databaseOnly' => 1
        ];

        Notification::send($receiverData, new ReceiveNotification($notificationData));

        \LogActivity::addToLog('Extensionist received an accomplishment.');

        return redirect()->route('extensionist.index')->with('success', 'Report has been added in department consolidation of reports.');
    
    }
    public function rejectCreate($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.to-receive.extensionists.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'extensionist',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'extensionist_approval' => 0
        ]);

        
        $report = Report::find($report_id);

        $returnData = User::find($report->user_id);
        $senderName = FacultyExtensionist::join('departments', 'departments.id', 'faculty_extensionists.department_id')
            ->join('users', 'users.id', 'faculty_extensionists.user_id')
            ->where('faculty_extensionists.department_id', $report->department_id)
            ->select('departments.code as department_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
            ->first();


        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = route('reports.consolidate.myaccomplishments');


        $notificationData = [
            'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_code.' Extensionist)',
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

        \LogActivity::addToLog('Extensionist returned an accomplishment.');

        return redirect()->route('extensionist.index')->with('success', 'Report has been returned to the owner.');
    }

    public function acceptSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $count = 0;
        foreach($reportIds as $report_id){
            Report::where('id', $report_id)->update(['extensionist_approval' => 1, 'chairperson_approval' => 1]);

            $report = Report::find($report_id);

            $receiverData = User::find($report->user_id);
            $senderName = FacultyExtensionist::join('departments', 'departments.id', 'faculty_extensionists.department_id')
                                ->join('users', 'users.id', 'faculty_extensionists.user_id')
                                ->where('faculty_extensionists.department_id', $report->department_id)
                                ->select('departments.code as department_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('reports.consolidate.myaccomplishments');


            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_code.' Extensionist)',
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

        \LogActivity::addToLog('Extensionist received '.$count.' accomplishments.');

        return redirect()->route('extensionist.index')->with('success', 'Report/s added in department consolidation of reports.');
    }

    public function denySelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');
        return view('reports.to-receive.extensionists.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualExtension();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        
        $reportIds = $request->input('report_id');

        $count = 0;
        foreach($reportIds as $report_id){
            if($request->input('reason_'.$report_id) == null)
                continue;
            Report::where('id', $ireport_idd)->update(['extensionist_approval' => 0]);
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'extensionist',
                'reason' => $request->input('reason_'.$report_id),
            ]);
                
            $report = Report::find($report_id);

            $returnData = User::find($report->user_id);
            $senderName = FacultyExtensionist::join('departments', 'departments.id', 'faculty_extensionists.department_id')
                ->join('users', 'users.id', 'faculty_extensionists.user_id')
                ->where('faculty_extensionists.department_id', $report->department_id)
                ->select('departments.code as department_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                ->first();


            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = route('reports.consolidate.myaccomplishments');


            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->department_code.' Extensionist)',
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

        \LogActivity::addToLog('Extensionist returned '.$count.' accomplishments.');

        return redirect()->route('extensionist.index')->with('success', 'Report/s returned to the owner/s.');

    }
}
