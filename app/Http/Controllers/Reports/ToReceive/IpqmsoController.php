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
    Maintenance\Department,
    Maintenance\ReportCategory,
};
use App\Notifications\ReceiveNotification;
use App\Notifications\ReturnNotification;
use App\Services\ToReceiveReportAuthorizationService;

class IpqmsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportsToReview = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
            ->join('departments', 'reports.department_id', 'departments.id')
            ->join('colleges', 'reports.college_id', 'colleges.id')
            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
            ->join('users', 'reports.user_id', 'users.id')
            ->where('chairperson_approval', 1)->where('dean_approval', 1)
            ->where('sector_approval', 1)->where('ipqmso_approval', null)->get();

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

        foreach($reportsToReview as $row){
            $row->report_details = json_decode($row->report_details, false);
        }

        return view('reports.to-receive.ipqmso.index', compact('reportsToReview', 'roles', 'departments', 'colleges', 'sectors', 'departmentsResearch','departmentsExtension'));
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
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['ipqmso_approval' => 1]);

        $report = Report::find($report_id);


        $receiverData = User::find($report->user_id);
        $senderName = User::where('id', auth()->id())
                            ->select('users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){

            if($report->department_id == 0){
                $url = route('reports.consolidate.college', $report->college_id);
                $acc_type="college";

                $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => "IPO",
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0,
                    'college_name' => $college_name,
                ];
            }
            else{
                $url = route('reports.consolidate.department', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => "IPO",
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0,
                    'department_name' => $department_name,
                ];
            }


        }
        else{
            $url = route('reports.consolidate.myaccomplishments');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => "IPO",
                'receiver' => $receiverData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $receiverData->id,
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 0
            ];

        }

        Notification::send($receiverData, new ReceiveNotification($notificationData));

        \LogActivity::addToLog('IPO received an accomplishment.');

        return redirect()->route('ipo.index')->with('success', 'Report has been added in college/branch/campus/office consolidation of reports.');
    }

    public function rejectCreate($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.to-receive.ipqmso.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'ipqmso',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'ipqmso_approval' => 0
        ]);

        $report = Report::find($report_id);

        $returnData = User::find($report->user_id);
        $senderName = User::where('id', auth()->id())
                        ->select('users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                        ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){

            if($report->department_id == 0){
                $url = route('report.manage', [$report_id, $report->report_category_id]);
                // $url = route('submissions.collegeaccomp.index', $report->college_id);
                $acc_type="college";

                $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => "IPO",
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0,
                    'college_name' => $college_name,

                ];
            }
            else{
                $url = route('report.manage', [$report_id, $report->report_category_id]);
                // $url = route('submissions.departmentaccomp.index', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => "IPO",
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

        }
        else{
            $url = route('report.manage', [$report_id, $report->report_category_id]);
            // $url = route('submissions.myaccomp.index');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => "IPO",
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

        \LogActivity::addToLog('IPO returned an accomplishment.');

        return redirect()->route('ipo.index')->with('deny-success', 'Report has been returned to the owner.');
    }

    public function undo($report_id){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        Report::where('id', $report_id)->update(['ipqmso_approval' => null]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Success');
    }

    public function acceptSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $count = 0;
        foreach($reportIds as $report_id){
            Report::where('id', $report_id)->update(['ipqmso_approval' => 1]);

            $report = Report::find($report_id);

            $receiverData = User::find($report->user_id);
            $senderName = User::where('id', auth()->id())
                                ->select('users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){

                if($report->department_id == 0){
                    $url = route('reports.consolidate.college', $report->college_id);
                    $acc_type="college";

                    $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => "IPO",
                        'receiver' => $receiverData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $receiverData->id,
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 0,
                        'college_name' => $college_name,
                    ];
                }
                else{
                    $url = route('reports.consolidate.department', $report->department_id);
                    $acc_type="department";

                    $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => "IPO",
                        'receiver' => $receiverData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $receiverData->id,
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 0,
                        'department_name' => $department_name,
                    ];
                }


            }
            else{
                $url = route('reports.consolidate.myaccomplishments');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => "IPO",
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 0
                ];

            }

            Notification::send($receiverData, new ReceiveNotification($notificationData));

            $count++;
        }

        \LogActivity::addToLog('IPO received '.$count.' accomplishments.');

        return redirect()->route('ipo.index')->with('success', 'Report/s added in college/branch/campus/office consolidation of reports.');
    }

    public function denySelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');
        return view('reports.to-receive.ipqmso.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $authorize = (new ToReceiveReportAuthorizationService())->authorizeReceiveIndividualToIpqmso();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $reportIds = $request->input('report_id');

        $count = 0;
        foreach($reportIds as $report_id){
            if($request->input('reason_'.$report_id) == null)
                continue;
            Report::where('id', $report_id)->update(['ipqmso_approval' => 0]);
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'ipqmso',
                'reason' => $request->input('reason_'.$report_id),
            ]);

            $report = Report::find($report_id);

            $returnData = User::find($report->user_id);
            $senderName = User::where('id', auth()->id())
                            ->select('users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){

                if($report->department_id == 0){
                    $url = route('report.manage', [$report_id, $report->report_category_id]);
                    // $url = route('submissions.collegeaccomp.index', $report->college_id);
                    $acc_type="college";

                    $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => "IPO",
                        'receiver' => $returnData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $returnData->id,
                        'reason' => $request->input('reason'),
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 0,
                        'college_name' => $college_name,

                    ];
                }
                else{
                    $url = route('report.manage', [$report_id, $report->report_category_id]);
                    // $url = route('submissions.departmentaccomp.index', $report->department_id);
                    $acc_type="department";

                    $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => "IPO",
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

            }
            else{
                $url = route('report.manage', [$report_id, $report->report_category_id]);
                // $url = route('submissions.myaccomp.index');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => "IPO",
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

        \LogActivity::addToLog('IPO returned '.$count.' accomplishments.');

        return redirect()->route('ipo.index')->with('success', 'Report/s returned to the owner/s.');

    }
}
