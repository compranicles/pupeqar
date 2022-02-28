<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertServiceConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nature' => rand(67, 70),
            'level' => rand(71, 74),
            'from' => date("Y-m-d"),
            'to' => date("Y-m-d", strtotime("+1 day")),
            'title' => $this->faker->text(),
            'venue' => $this->faker->text(),
            'partner_agency' => $this->faker->text(),
            'college_id' => 86,
            'department_id' => 292,
            'description' => $this->faker->word(),
            'user_id' => rand(3, 4)
        ];
    }
}
