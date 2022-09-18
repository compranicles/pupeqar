<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramField;

class ExtensionProgramFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtensionProgramField::truncate();

        $this->call([
            ExpertServiceConsultantFormSeeder::class,
            ExpertServiceConferenceFormSeeder::class,
            ExpertServiceAcademicFormSeeder::class,
            ExtensionServiceFormSeeder::class,
            PartnershipFormSeeder::class,
            MobilityFormSeeder::class,
            OutreachProgramFormSeeder::class,
            IntraMobilityFormSeeder::class,
            CommunityEngagementFormSeeder::class,
            OtherAccomplishmentFormSeeder::class,
            OtherDeptAccomplishmentFormSeeder::class,
            TechnicalExtensionFormSeeder::class,
        ]);
    }
}
