<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\Maintenance\Sector;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Models\Maintenance\College;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = Sector::all();
        return view('maintenances.sectors.index', compact('sectors'));
    }

    public function sync()
    {
        Artisan::call('db:seed', ['--class' => 'SectorSeeder']); 
        Artisan::call('db:seed', ['--class' => 'CollegeSeeder']); 
        Artisan::call('db:seed', ['--class' => 'DepartmentSeeder']); 

        return redirect()->route('sectors.maintenance.index')->with('sync_success', 'Sectors, Offices/Colleges/Branches/Campuses, and Departments data synced successfully');
    }

    public function getSectorName($collegeID){
        return College::where('colleges.id', $collegeID)
                        ->join('sectors', 'sectors.id', 'colleges.sector_id')
                        ->select('sectors.*')->get();
    }
}
