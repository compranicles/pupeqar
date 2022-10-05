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

class DeanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
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
                                        ->select('faculty_researchers.college_id', 'colleges.code')
                                        ->join('colleges', 'colleges.id', 'faculty_researchers.college_id')->get();
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
        $department_list = collect();
        $currentQuarterYear = Quarter::find(1);

        foreach ($colleges as $row){
            $tempReports = Report::where('reports.report_year', $currentQuarterYear->current_year)
                ->where('reports.report_quarter', $currentQuarterYear->current_quarter)
                ->where('reports.college_id', $row->college_id)->where('chairperson_approval', 1)
                ->where('dean_approval', null)
                ->select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->orderBy('reports.created_at', 'DESC')->get();

            $tempDepartment_list = Department::where('college_id', $row->college_id)
                ->orderBy('departments.name')
                ->select('departments.id', 'departments.name', 'colleges.name as college_name')
                ->join('colleges', 'colleges.id', 'departments.college_id')
                ->get();

            $reportsToReview = $reportsToReview->concat($tempReports);
            $department_list = $department_list->concat($tempDepartment_list);
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

        return view('reports.to-receive.deans.index', compact('reportsToReview', 'roles', 'departments', 'colleges', 'college_names', 'department_names', 'department_list', 'sectors', 'departmentsResearch','departmentsExtension'));
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

    public function accept($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        
        $report = Report::find($report_id);

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        
        $receiverData = User::find($report->user_id);
        
        if (in_array(12, $roles)) {
            Report::where('id', $report_id)->update(['dean_approval' => 2]); //associates
            $senderName = Associate::join('colleges', 'colleges.id', 'associates.college_id')
                                ->join('users', 'users.id', 'associates.user_id')
                                ->where('associates.college_id', $report->college_id)
                                ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();
        }
        if (in_array(6, $roles)) {
            Report::where('id', $report_id)->update(['dean_approval' => 1]); //associates
            $senderName = Dean::join('colleges', 'colleges.id', 'deans.college_id')
                                ->join('users', 'users.id', 'deans.user_id')
                                ->where('deans.college_id', $report->college_id)
                                ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();
        }

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){
            $url = route('reports.consolidate.department', $report->department_id);
            $acc_type="department";

            $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                'receiver' => $receiverData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $receiverData->id,
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 1,
                'department_name' => $department_name,
            ];

        }
        else{
            $url = route('reports.consolidate.myaccomplishments');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                'receiver' => $receiverData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $receiverData->id,
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 1
            ];

        }

        Notification::send($receiverData, new ReceiveNotification($notificationData));

        \LogActivity::addToLog('Dean received an accomplishment.');

        return redirect()->route('director.index')->with('success', 'Report has been added in college/branch/campus consolidation of reports.');
    }

    public function rejectCreate($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.to-receive.deans.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        if (in_array(12, $roles)) {
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'Associate/Assistant Dean/Director',
                'reason' => $request->input('reason'),
            ]);
        }

        if (in_array(6, $roles)) {
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'Dean/Director',
                'reason' => $request->input('reason'),
            ]);
        }

        Report::where('id', $report_id)->update([
            'dean_approval' => 0
        ]);

        $report = Report::find($report_id);

        $returnData = User::find($report->user_id);

        if (in_array(12, $roles)) {
            $senderName = Associate::join('colleges', 'colleges.id', 'associates.college_id')
                                ->join('users', 'users.id', 'associates.user_id')
                                ->where('associates.college_id', $report->college_id)
                                ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();
        }
        if (in_array(6, $roles)) {
            $senderName = Dean::join('colleges', 'colleges.id', 'deans.college_id')
                                ->join('users', 'users.id', 'deans.user_id')
                                ->where('deans.college_id', $report->college_id)
                                ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();
        }

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){
            $url = route('report.manage', [$report_id, $report->report_category_id]);
            // $url = route('submissions.departmentaccomp.index', $report->department_id);
            $acc_type="department";

            $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                'receiver' => $returnData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $returnData->id,
                'reason' => $request->input('reason'),
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 0,
                'department_name' => $department_name,

            ];
        }
        else{
            $url = route('report.manage', [$report_id, $report->report_category_id]);
            // $url = route('submissions.myaccomp.index');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                'receiver' => $returnData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $returnData->id,
                'reason' => $request->input('reason'),
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 0
            ];

        }


        Notification::send($returnData, new ReturnNotification($notificationData));

        if (in_array(12, $roles)) {
            \LogActivity::addToLog('Associate/Assistant Dean/Director returned an accomplishment.');
        }
        if (in_array(6, $roles)) {
            \LogActivity::addToLog('Dean/Director returned an accomplishment.');
        }

        return redirect()->route('director.index')->with('success', 'Report has been returned to the owner.');
    }

    public function relay($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['dean_approval' => 0]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Report has been returned to the owner.');
    }

    public function undo($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['dean_approval' => null]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Success');
    }

    public function acceptSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $count = 0;
        foreach($reportIds as $report_id){
            
            $report = Report::find($report_id);
            
            $receiverData = User::find($report->user_id);
            if (in_array(12, $roles)) {
                Report::where('id', $report_id)->update(['dean_approval' => 2]); //associates
                $senderName = Associate::join('colleges', 'colleges.id', 'associates.college_id')
                                    ->join('users', 'users.id', 'associates.user_id')
                                    ->where('associates.college_id', $report->college_id)
                                    ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                    ->first();
            }
            if (in_array(6, $roles)) {
                Report::where('id', $report_id)->update(['dean_approval' => 1]); 
                $senderName = Dean::join('colleges', 'colleges.id', 'deans.college_id')
                                    ->join('users', 'users.id', 'deans.user_id')
                                    ->where('deans.college_id', $report->college_id)
                                    ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                    ->first();
            }

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){
                $url = route('reports.consolidate.department', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1,
                    'department_name' => $department_name,

                ];

            }
            else{
                $url = route('reports.consolidate.myaccomplishments');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1
                ];

            }

            Notification::send($receiverData, new ReceiveNotification($notificationData));
            $count++;
        }

        \LogActivity::addToLog('Dean received '.$count.' accomplishments.');

        return redirect()->route('director.index')->with('success', 'Report/s added in college/branch/campus consolidation of reports.');
    }

    public function denySelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');
        return view('reports.to-receive.deans.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $count = 0;
        foreach($reportIds as $report_id){
            if($request->input('reason_'.$report_id) == null)
                continue;
            Report::where('id', $report_id)->update(['dean_approval' => 0]);
            if (in_array(12, $roles)) {
                DenyReason::create([
                    'report_id' => $report_id,
                    'user_id' => auth()->id(),
                    'position_name' => 'Associate/Assistant Dean/Director',
                    'reason' => $request->input('reason_'.$report_id),
                ]);
            }
    
            if (in_array(6, $roles)) {
                DenyReason::create([
                    'report_id' => $report_id,
                    'user_id' => auth()->id(),
                    'position_name' => 'Dean/Director',
                    'reason' => $request->input('reason_'.$report_id),
                ]);
            }
    
            $report = Report::find($report_id);

            $returnData = User::find($report->user_id);
            if (in_array(12, $roles)) {
                $senderName = Associate::join('colleges', 'colleges.id', 'associates.college_id')
                                    ->join('users', 'users.id', 'associates.user_id')
                                    ->where('associates.college_id', $report->college_id)
                                    ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                    ->first();
            }
            if (in_array(6, $roles)) {
                $senderName = Dean::join('colleges', 'colleges.id', 'deans.college_id')
                                    ->join('users', 'users.id', 'deans.user_id')
                                    ->where('deans.college_id', $report->college_id)
                                    ->select('colleges.code as college_code', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                    ->first();
            }

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){
                $url = route('report.manage', [$report_id, $report->report_category_id]);
                // $url = route('submissions.departmentaccomp.index', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0,
                    'department_name' => $department_name,

                ];
            }
            else{
                $url = route('report.manage', [$report_id, $report->report_category_id]);
                // $url = route('submissions.myaccomp.index');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->college_code.')',
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0
                ];

            }


            Notification::send($returnData, new ReturnNotification($notificationData));
            $count++;
        }

        if (in_array(12, $roles)) {
            \LogActivity::addToLog('Associate/Assistant Dean/Director returned '.$count.' accomplishments.');
        }
        if (in_array(6, $roles)) {
            \LogActivity::addToLog('Dean/Director returned '.$count.' accomplishments.');
        }
        return redirect()->route('director.index')->with('success', 'Report/s returned to the owner/s.');
    }
}
