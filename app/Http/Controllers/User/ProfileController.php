<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function personal() {
        return view('profile.personal-profile');
    }

    // public function employment() {
    //     return view('profile.employment-profile');
    // }
    public function educationalBackground() {
        return view('profile.educational-bg-profile');
    }

    public function educationalDegree() {
        return view('profile.educational-degree-profile');
    }

    public function eligibility() {
        return view('profile.eligibility-profile');
    }

    public function workExperience() {
        return view('profile.work-experience-profile');
    }

    public function voluntaryWork() {
        return view('profile.voluntary-work-profile');
    }
    // public function professionalStudy() {
    //     return view('profile.professional-study-profile');
    // }

    // public function teaching() {
    //     return view('profile.teaching-profile');
    // }
}
