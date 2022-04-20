<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'classification' => rand(46, 48),
            'nature' => $this->faker->text(),
            'title' => $this->faker->text(),
            'collaborator' => date("Y-m-d"),
            'funding_type' => rand(49, 51),
            'funding_amount' => $this->faker->randomFloat(2),
            'funding_agency' => $this->faker->text(),
            'status' => 53,
            'start_date' => date("Y-m-d"),
            'end_date' =>  date("Y-m-d", strtotime("+1 day")),
            'utilization' => $this->faker->text(),
            'copyright_number' => $this->faker->word(),
            'issue_date' =>  date("Y-m-d", strtotime("+2 day")),
            'college_id' => 88,
            'department_id' => 296,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 2,
            'report_year' => 2022,
        ];
    }
}
