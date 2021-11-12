<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\ReportType;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportType::truncate();
        ReportType::insert([
            ['name' => 'Individual'],
            ['name' => 'Department'],
            ['name' => 'College']
        ]);
    }
}
