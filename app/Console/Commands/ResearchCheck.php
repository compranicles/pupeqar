<?php

namespace App\Console\Commands;

use DateTime;
use App\Models\User;
use App\Models\Research;
use Illuminate\Console\Command;
use App\Notifications\ResearchNotification;
use Illuminate\Support\Facades\Notification;

class ResearchCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'research:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking if the research is less than a month from due and sends email to researchers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $researches = Research::whereIn('nature_of_involvement', [11, 224])->where('status', 27)->get();


        foreach ($researches as $research){
            $target_date = new DateTime($research->target_date);
            $current_date = new DateTime(date('Y-m-d'));
            $interval = $current_date->diff($target_date);
            if($interval->days <= 30){
                $user = User::find($research->user_id);
                $url = route('research.show', $research->id);

                $notificationData = [
                    'sender' => 'PUP eQAR',
                    'receiver' => $user->first_name,
                    'url' => $url,
                    'research_title' => $research->title,
                    'research_code' => $research->research_code,
                    'target_date' => $target_date->format('F j, Y'),
                    'user_id' => $user->id,
                    'days_remaining' => $interval->days,
                    'type' => 'research',
                    'date' => date('F j, Y, g:i a'),
                ];

                Notification::send($user, new ResearchNotification($notificationData));
                
            }
        }
    }
}
