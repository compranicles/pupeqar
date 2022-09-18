<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function index(){
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('optimize:clear');

        return redirect()->route('home');
    }

    public function migrate(){
        \Artisan::call('migrate');
        \Artisan::call('db:seed');


        return redirect()->route('home');
    }
}

