<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SyllabusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'course_title' => $this->faker->text(),
            'assigned_task' => rand(186, 189),
            'date_finished' => date("Y-m-d"),
            'college_id' => 88,
            'department_id' => 296,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 2,
            'report_year' => 2022,
            // User::all()->random()->id,
        ];
    }
}
