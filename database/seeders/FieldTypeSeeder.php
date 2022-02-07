<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('field_types')->truncate();
        DB::table('field_types')->insert(['name' => 'text']);
        DB::table('field_types')->insert(['name' => 'number']);
        DB::table('field_types')->insert(['name' => 'currency-decimal']);
        DB::table('field_types')->insert(['name' => 'date']);
        DB::table('field_types')->insert(['name' => 'dropdown']);
        DB::table('field_types')->insert(['name' => 'date-range']);
        DB::table('field_types')->insert(['name' => 'multi-select']);
        DB::table('field_types')->insert(['name' => 'textarea']);
        DB::table('field_types')->insert(['name' => 'file-upload']);
        DB::table('field_types')->insert(['name' => 'multiple-file-upload']); //10
        DB::table('field_types')->insert(['name' => 'decimal']);
        DB::table('field_types')->insert(['name' => 'college']);
        DB::table('field_types')->insert(['name' => 'department']);
        DB::table('field_types')->insert(['name' => 'yes-no']);
        DB::table('field_types')->insert(['name' => 'percentage']);
        DB::table('field_types')->insert(['name' => 'document_description']); //16
    }
}
