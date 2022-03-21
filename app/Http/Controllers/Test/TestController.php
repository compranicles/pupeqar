<?php

namespace App\Http\Controllers\Test;

use DateTime;
use App\Models\User;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\Maintenance\Sector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\ResearchNotification;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
   public function index() { 
        // $db_ext = DB::connection('mysql_external');
        // // dd($db_ext);
        // $departments = $db_ext->select(" EXEC GetDepartment");

        // $departmentIDs = []; 
        // $count = 0;

        // // foreach($departments as $row){
        // //     $departmentIDs[$count] = $row->DepartmentID;
        // //     $count++;
        // // }
        
        // // $sectorHRISCode = Sector::pluck('hris_code')->all();
        // // $allDepartments = $db_ext->select(" EXEC GetDepartment");
        // // dd(Sector::where('hris_code', $allDepartments[282]->DepartmentID)->pluck('id')->first());
        // $i = 1;
        // $totalcount = 1;
        // do {
        //     $data = $db_ext->select(" EXEC GetUserAccount $i");
        //     $count++;
        //     $i++;
        // }
        // while (count($data) > 0);

        // echo $count;
        // echo $i++;
        // var_dump($data);

        // $researches = Research::whereIn('nature_of_involvement', [11, 224])->where('status', 27)->get();


        // foreach ($researches as $research){
        //     $target_date = new DateTime($research->target_date);
        //     $current_date = new DateTime(date('Y-m-d'));
        //     $interval = $current_date->diff($target_date);
        //     if($interval->days < 30){
        //         $user = User::find($research->user_id);
        //         $url = route('research.show', $research->id);

        //         $notificationData = [
        //             'sender' => 'PUP eQAR',
        //             'receiver' => $user->first_name,
        //             'url' => $url,
        //             'research_title' => $research->title,
        //             'research_code' => $research->research_code,
        //             'target_date' => $target_date->format('F j, Y'),
        //             'user_id' => $user->id,
        //             'days_remaining' => $interval->days,
        //             'type' => 'research',
        //             'date' => date('F j, Y, g:i a'),
        //         ];

        //         Notification::send($user, new ResearchNotification($notificationData));
                
        //     }
        // }

        // return true;

    }
}
