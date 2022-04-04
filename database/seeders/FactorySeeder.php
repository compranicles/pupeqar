<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        \App\Models\Research::truncate();
        \App\Models\Research::factory()->count(12)->create();

        \App\Models\Invention::truncate();
        \App\Models\Invention::factory()->count(12)->create();

        \App\Models\Mobility::truncate();
        \App\Models\Mobility::factory()->count(12)->create();

        \App\Models\Partnership::truncate();
        \App\Models\Partnership::factory()->count(12)->create();

        // \App\Models\ExtensionService::truncate();
        // \App\Models\ExtensionService::factory()->count(12)->create();

        \App\Models\ExpertServiceAcademic::truncate();
        \App\Models\ExpertServiceAcademic::factory()->count(12)->create();

        \App\Models\ExpertServiceConference::truncate();
        \App\Models\ExpertServiceConference::factory()->count(12)->create();

        \App\Models\ExpertServiceConsultant::truncate();
        \App\Models\ExpertServiceConsultant::factory()->count(12)->create();

        \App\Models\Reference::truncate();
        \App\Models\Reference::factory()->count(12)->create();

        \App\Models\Syllabus::truncate();
        \App\Models\Syllabus::factory()->count(12)->create();
    }
}
