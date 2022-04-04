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
            'college_id' => 87,
            'department_id' => 292,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 1,
            'report_year' => 2022,
        ];
    }
}
