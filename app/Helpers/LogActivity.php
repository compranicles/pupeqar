<?php

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{


    public static function addToLog($subject)
    {
    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->id() : null;
    	LogActivityModel::create($log);
    }


    public static function logActivityLists()
    {
    	return LogActivityModel::join('users', 'users.id', 'log_activities.user_id')
					->select('log_activities.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->latest()->get();
    }

	public static function logActivityListsTen()
    {
    	return LogActivityModel::join('users', 'users.id', 'log_activities.user_id')
					->select('log_activities.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->latest()->take(10)->get();
    }

	public static function logActivityListsPerIdTen()
    {
    	return LogActivityModel::where('user_id', auth()->id())->latest()->take(10)->get();
    }
	public static function logActivityListsPerId()
    {
    	return LogActivityModel::where('user_id', auth()->id())->latest()->get();
    }
}