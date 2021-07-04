<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function showMessage($id){
        return Announcement::findOrFail($id);
   }
}