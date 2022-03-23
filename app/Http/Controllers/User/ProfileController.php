<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Authentication\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{   
    public function personal() {

        $user = User::find(auth()->id());
        
        $db_ext = DB::connection('mysql_external');

        $employeeDetail1 = $db_ext->select("EXEC GetEmployeeByEmpCode N'$user->emp_code'");
        $employeeDetail2 = $db_ext->select("EXEC GetEmployeePersonalDetailsByEmpCode N'$user->emp_code'");
        $employeeDetail3 = $db_ext->select("EXEC GetUserAccount N'$user->user_account_id'");
        $nationalities = $db_ext->select("EXEC GetNationality");
        $countries = $db_ext->select("EXEC GetCountry");
        $civilStatuses = $db_ext->select("EXEC GetCivilStatus");
        $religions = $db_ext->select("EXEC GetReligion");

        //Citizenship
        $citizenship;
        foreach($nationalities as $nationality){
            if($nationality->NationalityID == $employeeDetail2[0]->NationalityID)
                $citizenship = $nationality->Nationality;
        }
        if($employeeDetail2[0]->IsDualCitizenship == 'Y'){
            $citizenship2;
            foreach($countries as $country){
                if($country->CountryID == $employeeDetail2[0]->DualCitizenshipCountryID)
                    $citizenship = $citizenship.', '.$country->Country.' ('.$employeeDetail2[0]->DualCitizenshipBy.')';
            }
        }
        //CivilStatus
        $civilStatus;
        foreach($civilStatuses as $status){
            if($status->CivilStatusID == $employeeDetail2[0]->CivilStatusID)
                $civilStatus = $status->CivilStatus;
        }
        //Religion
        $religion;
        foreach($religions as $row)
            if($row->ReligionID == $employeeDetail2[0]->ReligionID)
                $religion = $row->Religion;
        //PlaceOfBirth
        $placeOfBirth = $employeeDetail2[0]->CityMunicipality.', '.$employeeDetail2[0]->Province.', '.$employeeDetail2[0]->Region.', '.$employeeDetail2[0]->BCountry;

        return view('profile.personal-profile', compact('employeeDetail1', 'employeeDetail2', 'employeeDetail3', 'citizenship', 'civilStatus', 'placeOfBirth', 'religion'));
    }

    // public function employment() {
    //     return view('profile.employment-profile');
    // }
    public function educationalBackground() {
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $educationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");

        $educationFinal = [];

        foreach($educationLevel as $level){

            $educationTemp = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndEducationLevelID N'$user->emp_code',$level->EducationLevelID");

            $educationFinal = array_merge($educationFinal, $educationTemp);
        }

        $educationDisciplines = $db_ext->select("EXEC GetEducationDiscipline");

        return view('profile.educational-bg-profile', compact('educationLevel', 'educationFinal', 'educationDisciplines'));
    }

    public function educationalDegree() {
        return view('profile.educational-degree-profile');
    }

    public function eligibility() {
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $employeeEligibilities = $db_ext->select("EXEC GetEmployeeEligibilityByEmpCode N'$user->emp_code'");

        return view('profile.eligibility-profile', compact('employeeEligibilities'));
    }

    public function workExperience() {
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $workExperiences = $db_ext->select("EXEC GetEmployeeWorkExperienceByEmpCode N'$user->emp_code'");

        return view('profile.work-experience-index', compact('workExperiences'));
    }

    public function workExperienceView($id){
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $workExperience = $db_ext->select("EXEC GetEmployeeWorkExperienceByEmpCodeAndID N'$user->emp_code','$id'");

        $employmentStatuses = $db_ext->select("EXEC GetEmploymentStatus");

        $employeeStatus;
        foreach($employmentStatuses as $status)
            if($workExperience[0]->EmploymentStatusID == $status->EmploymentStatusID)
                $employeeStatus = $status->EmploymentStatus;

        return view('profile.work-experience-profile', compact('workExperience', 'employeeStatus'));
    }

    public function voluntaryWork() {
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $voluntaryWorks = $db_ext->select("EXEC GetEmployeeVoluntaryWorkByEmpCode N'$user->emp_code'");

        return view('profile.voluntary-work-index', compact('voluntaryWorks'));
    }

    // public function professionalStudy() {
    //     return view('profile.professional-study-profile');
    // }

    // public function teaching() {
    //     return view('profile.teaching-profile');
    // }
}
