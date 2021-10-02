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
        DB::table('field_types')->insert(['name' => 'text']);
        DB::table('field_types')->insert(['name' => 'textarea']);
        DB::table('field_types')->insert(['name' => 'number']);
        DB::table('field_types')->insert(['name' => 'decimal']);
        DB::table('field_types')->insert(['name' => 'date']);
        DB::table('field_types')->insert(['name' => 'checkbox']);
        DB::table('field_types')->insert(['name' => 'dropdown']);
        DB::table('field_types')->insert(['name' => 'radio']);
        DB::table('field_types')->insert(['name' => 'multi-select']);
        DB::table('field_types')->insert(['name' => 'datalist']);
        DB::table('field_types')->insert(['name' => 'file-upload']);
        DB::table('field_types')->insert(['name' => 'reset']);
        DB::table('field_types')->insert(['name' => 'submit']);
        DB::table('field_types')->insert(['name' => 'hidden']);
        DB::table('field_types')->insert(['name' => 'time']);
        DB::table('field_types')->insert(['name' => 'search']);
        DB::table('field_types')->insert(['name' => 'email']);
        DB::table('field_types')->insert(['name' => 'mobile']);
        DB::table('field_types')->insert(['name' => 'date-range']);
    }
}
