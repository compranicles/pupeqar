<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\Maintenance\Sector;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

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
}
