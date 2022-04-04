<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ViableProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomNumber(4, true),
            'revenue' => rand(131, 137),
            'cost' => rand(139, 148),
            'start_date' => $this->faker->word(),
            'rate_of_return' => $this->faker->word(),
            'description' => $this->faker->word(),
            'user_id' => 4,
            'report_quarter' => 1,
            'report_year' => 2022,
        ];
    }
}

