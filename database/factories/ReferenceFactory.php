<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category' => rand(177, 180),
            'level' => rand(181, 185),
            'date_started' => date("Y-m-d"),
            'date_completed' => date("Y-m-d", strtotime("+1 day")),
            'title' => $this->faker->text(),
            'authors_compilers' => $this->faker->name.', '.$this->faker->name.', '.$this->faker->name,
            'editor_name' => $this->faker->name.', '.$this->faker->name.', '.$this->faker->name,
            'editor_profession' => $this->faker->word(),
            'volume_no' => rand(1, 5),
            'issue_no' => rand(1, 5),
            'date_published' => date("Y-m-d", strtotime("+3 day")),
            'copyright_regi_no' => $this->faker->numerify('ISBN-####'),
            'college_id' => 88,
            'department_id' => 296,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 2,
            'report_year' => 2022,
        ];
    }
}