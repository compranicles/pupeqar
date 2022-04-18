<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MobilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nature_of_engagement' => rand(163, 169),
            'type' => rand(170, 172),
            'host_name' => $this->faker->text(),
            'host_address' => $this->faker->text(),
            'mobility_description' => $this->faker->text(),
            'start_date' => date("Y-m-d"),
            'end_date' => date("Y-m-d", strtotime("+1 day")),
            'college_id' => 1,
            'department_id' => 2,
            'description' => $this->faker->word(),
            'user_id' => 5,
            'report_quarter' => 2,
            'report_year' => 2022,
        ];
    }
}
