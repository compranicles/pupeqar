<?php

namespace App\Console\Commands;

use DateTime;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Maintenance\Quarter;
use App\Notifications\DeadlineNotification;
use Illuminate\Support\Facades\Notification;

class DeadlineDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if today is 5 days or less before the deadline and will email everyone';

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
        $deadlineData = Quarter::find(1);

        $deadline_date = new DateTime($deadlineData->deadline);
        $current_date = new DateTime(date('Y-m-d'));
        
        $interval = $current_date->diff($deadline_date);

        if($interval->days <= 5){
            $users = User::all();
            foreach ($users as $user){
                if($user->id >= 67){
                    $url = route('dashboard');

                    $notificationData = [
                        'sender' => 'PUP eQAR',
                        'receiver' => $user->first_name,
                        'url' => $url,
                        'deadline_date' => $deadline_date->format('F j, Y'),
                        'user_id' => $user->id,
                        'days_remaining' => $interval->days,
                        'type' => 'deadline',
                        'quarter' => $deadlineData->current_quarter,
                        'year' => $deadlineData->current_year,
                        'date' => date('F j, Y, g:i a'),
                    ];

                    Notification::send($user, new DeadlineNotification($notificationData));
                }
            }
        }
    }
}
