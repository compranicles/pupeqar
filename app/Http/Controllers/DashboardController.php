<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Research;
use App\Models\Invention;
use App\Models\Syllabus;
use App\Models\Reference;
use App\Models\ExpertServiceConsultant;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceAcademic;
use App\Models\ExtensionService;
use App\Models\Partnership;
use App\Models\Mobility;
use App\Models\Request as RequestModel;

class DashboardController extends Controller
{
    public function index() {
        $research = Research::select()->where('research.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $inventions = Invention::select()->where('inventions.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $syllabus = Syllabus::select()->where('syllabi.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $references = Reference::select()->where('references.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esconsultant = ExpertServiceConsultant::select()->where('expert_service_consultants.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esconference = ExpertServiceConference::select()->where('expert_service_conferences.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $esacademic = ExpertServiceAcademic::select()->where('expert_service_academics.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $es = ExtensionService::select()->where('extension_services.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $partnerships = Partnership::select()->where('partnerships.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $mobilities = Mobility::select()->where('mobilities.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        $requests = RequestModel::select()->where('requests.user_id', auth()->id())->where("updated_at", '>=', 'DATEADD(MONTH, -3, GETDATE())')->count();
        

        $facultysum = $research + $inventions + $syllabus + $references + $esconsultant + 
                    $esconference + $esacademic + $es + $partnerships +$mobilities + $requests
                    ;


            return view('dashboard', compact('facultysum'));
    }
}
