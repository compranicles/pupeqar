<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function personal() {
        return view('profile.personal-profile');
    }

    public function employment() {
        return view('profile.employment-profile');
    }

    public function education() {
        return view('profile.education-profile');
    }

    public function professionalStudy() {
        return view('profile.professional-study-profile');
    }

    public function teaching() {
        return view('profile.teaching-profile');
    }
}
