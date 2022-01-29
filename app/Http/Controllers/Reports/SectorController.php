<?php

namespace App\Http\Controllers\Reports;

use App\Models\Dean;
use App\Models\User;
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
use App\Notifications\ReturnNotification;
use App\Models\Maintenance\ReportCategory;
use App\Notifications\ReceiveNotification;
use Illuminate\Support\Facades\Notification;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $reportsToReview = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as dept_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
            ->join('colleges', 'reports.college_id', 'colleges.id')
            ->join('departments', 'reports.department_id', 'departments.id')
            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
            ->join('users', 'reports.user_id', 'users.id')
            ->where('chairperson_approval', 1)->where('dean_approval', 1)
            ->where('sector_approval', null)->get();
        
        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments_nav = [];
        $colleges_nav = [];
        $sectors = [];
        $departmentsResearch = [];
        $departmentsExtension = [];
       
        if(in_array(5, $roles)){
            $departments_nav = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges_nav = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
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
            
        $colleges = College::select('colleges.id', 'colleges.name')
                            ->orderBy('colleges.name')
                            ->get();
        return view('reports.sector.index', compact('reportsToReview', 'roles', 'departments_nav', 'colleges_nav', 'colleges', 'sectors', 'departmentsResearch','departmentsExtension'));
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
        Report::where('id', $report_id)->update(['sector_approval' => 1]);

        $report = Report::find($report_id);
        
        
        $receiverData = User::find($report->user_id);
        $senderName = SectorHead::join('sectors', 'sectors.id', 'sector_heads.sector_id')
                            ->join('users', 'users.id', 'sector_heads.user_id')
                            ->where('sector_heads.college_id', $report->college_id)
                            ->select('sectors.name as sector_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){

            if($report->department_id == 0){
                $url = route('submissions.collegeaccomp.index', $report->college_id);
                $acc_type="college";

                $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                    'receiver' => $receiverData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $receiverData->id,
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1,
                    'college_name' => $college_name,
                ];
            }
            else{
                $url = route('submissions.departmentaccomp.index', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
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
            

        }
        else{
            $url = route('submissions.myaccomp.index');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
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

        return redirect()->route('sector.index')->with('success', 'Report has been added in consolidated report.');
    }

    public function rejectCreate($report_id){
        return view('reports.sector.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'sector',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'sector_approval' => 0
        ]);

        $report = Report::find($report_id);

        $returnData = User::find($report->user_id);
        $senderName = SectorHead::join('sectors', 'sectors.id', 'sector_heads.sector_id')
                        ->join('users', 'users.id', 'sector_heads.user_id')
                        ->where('sector_heads.college_id', $report->college_id)
                        ->select('sectors.name as sector_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                        ->first();

        $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

        $url = '';
        $acc_type = '';
        if($report->report_category_id > 16 ){

            if($report->department_id == 0){
                $url = route('submissions.collegeaccomp.index', $report->college_id);
                $acc_type="college";

                $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1,
                    'college_name' => $college_name,

                ];
            }
            else{
                $url = route('submissions.departmentaccomp.index', $report->department_id);
                $acc_type="department";

                $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1,
                    'department_name' => $department_name,

                ];
            }
            
        }
        else{
            $url = route('submissions.myaccomp.index');
            $acc_type = 'individual';

            $notificationData = [
                'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                'receiver' => $returnData->first_name,
                'url' => $url,
                'category_name' => $report_category_name,
                'user_id' => $returnData->id,
                'reason' => $request->input('reason'),
                'accomplishment_type' => $acc_type,
                'date' => date('F j, Y, g:i a'),
                'databaseOnly' => 1
            ];
    
        }

        
        Notification::send($returnData, new ReturnNotification($notificationData));

        return redirect()->route('sector.index')->with('success', 'Report has been returned.');
    }

    public function relay($report_id){
        Report::where('id', $report_id)->update(['sector_approval' => 0]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Report Denial successfully sent');
    }

    public function undo($report_id){
        Report::where('id', $report_id)->update(['sector_approval' => null]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Success');
    }

    public function acceptSelected(Request $request){
        $reportIds = $request->input('report_id');

        foreach($reportIds as $report_id){
            Report::where('id', $report_id)->update(['sector_approval' => 1]);

            $report = Report::find($report_id);
        
        
            $receiverData = User::find($report->user_id);
            $senderName = SectorHead::join('sectors', 'sectors.id', 'sector_heads.sector_id')
                                ->join('users', 'users.id', 'sector_heads.user_id')
                                ->where('sector_heads.college_id', $report->college_id)
                                ->select('sectors.name as sector_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                                ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){

                if($report->department_id == 0){
                    $url = route('submissions.collegeaccomp.index', $report->college_id);
                    $acc_type="college";

                    $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                        'receiver' => $receiverData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $receiverData->id,
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 1,
                        'college_name' => $college_name,
                    ];
                }
                else{
                    $url = route('submissions.departmentaccomp.index', $report->department_id);
                    $acc_type="department";

                    $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
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
                

            }
            else{
                $url = route('submissions.myaccomp.index');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
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
        }
        return redirect()->route('sector.index')->with('success', 'Report/s Approved Successfully');
    }

    public function denySelected(Request $request){
        $reportIds = $request->input('report_id');
        return view('reports.sector.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $reportIds = $request->input('report_id');
        foreach($reportIds as $report_id){
            if($request->input('reason_'.$report_id) == null)
                continue;
            Report::where('id', $report_id)->update(['sector_approval' => 0]);
            DenyReason::create([
                'report_id' => $report_id,
                'user_id' => auth()->id(),
                'position_name' => 'sector',
                'reason' => $request->input('reason_'.$report_id),
            ]);

            
            $report = Report::find($report_id);

            $returnData = User::find($report->user_id);
            $senderName = SectorHead::join('sectors', 'sectors.id', 'sector_heads.sector_id')
                            ->join('users', 'users.id', 'sector_heads.user_id')
                            ->where('sector_heads.college_id', $report->college_id)
                            ->select('sectors.name as sector_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix')
                            ->first();

            $report_category_name = ReportCategory::where('id', $report->report_category_id)->pluck('name')->first();

            $url = '';
            $acc_type = '';
            if($report->report_category_id > 16 ){

                if($report->department_id == 0){
                    $url = route('submissions.collegeaccomp.index', $report->college_id);
                    $acc_type="college";

                    $college_name = College::where('id', $report->college_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                        'receiver' => $returnData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $returnData->id,
                        'reason' => $request->input('reason'),
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 1,
                        'college_name' => $college_name,

                    ];
                }
                else{
                    $url = route('submissions.departmentaccomp.index', $report->department_id);
                    $acc_type="department";

                    $department_name = Department::where('id', $report->department_id)->pluck('name')->first();

                    $notificationData = [
                        'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                        'receiver' => $returnData->first_name,
                        'url' => $url,
                        'category_name' => $report_category_name,
                        'user_id' => $returnData->id,
                        'reason' => $request->input('reason'),
                        'accomplishment_type' => $acc_type,
                        'date' => date('F j, Y, g:i a'),
                        'databaseOnly' => 1,
                        'department_name' => $department_name,

                    ];
                }
                
            }
            else{
                $url = route('submissions.myaccomp.index');
                $acc_type = 'individual';

                $notificationData = [
                    'sender' => $senderName->first_name.' '.$senderName->middle_name.' '.$senderName->last_name.' '.$senderName->suffix.' ('.$senderName->sector_name.')',
                    'receiver' => $returnData->first_name,
                    'url' => $url,
                    'category_name' => $report_category_name,
                    'user_id' => $returnData->id,
                    'reason' => $request->input('reason'),
                    'accomplishment_type' => $acc_type,
                    'date' => date('F j, Y, g:i a'),
                    'databaseOnly' => 1
                ];
        
            }

            
            Notification::send($returnData, new ReturnNotification($notificationData));
        }
        return redirect()->route('sector.index')->with('success', 'Report/s Denied Successfully');

    }
}
