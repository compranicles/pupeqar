<?php

namespace App\Http\Controllers\Hap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(){
        return view('hap.review.index');
    }
}
