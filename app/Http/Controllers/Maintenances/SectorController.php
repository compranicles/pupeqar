<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Models\Maintenance\{
    College,
    Sector,
};

class SectorController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Sector::class);
        $sectors = Sector::all();
        return view('maintenances.sectors.index', compact('sectors'));
    }

    public function sync()
    {
        $this->authorize('update', Sector::class);
        Schema::disableForeignKeyConstraints();

        Artisan::call('db:seed', ['--class' => 'SectorSeeder']); 
        Artisan::call('db:seed', ['--class' => 'CollegeSeeder']); 
        Artisan::call('db:seed', ['--class' => 'DepartmentSeeder']); 

        Schema::enableForeignKeyConstraints();



        return redirect()->route('sectors.maintenance.index')->with('sync_success', 'Sectors, Offices/Colleges/Branches/Campuses, and Departments data synced successfully');
    }

    public function getSectorName($collegeID){
        return College::where('colleges.id', $collegeID)
                        ->join('sectors', 'sectors.id', 'colleges.sector_id')
                        ->select('sectors.*')->get();
    }
}
