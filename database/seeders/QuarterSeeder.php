<?php

namespace Database\Seeders;

use DateTime;
use DateInterval;
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
        $date = new DateTime();
        $date->add(new DateInterval('P15D'));
        Quarter::create([
            'current_quarter' => '1',
            'current_year' => '2022',
            'deadline' => $date->format('Y-m-d'),
        ]);
    }
}
