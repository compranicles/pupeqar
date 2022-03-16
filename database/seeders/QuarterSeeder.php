<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\Quarter;

class QuarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Quarter::truncate();
        Quarter::create([
            'current_quarter' => '1',
            'current_year' => '2022',
        ]);
    }
}
