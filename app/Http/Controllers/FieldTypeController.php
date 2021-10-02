<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    public function index(){
        return view('formbuilder.fieldtype.index');
    }
}
