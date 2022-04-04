<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertServiceConsultantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'classification' => rand(56, 60),
            'category' => rand(61, 62),
            'level' => rand(63, 66),
            'from' => date("Y-m-d"),
            'to' => date("Y-m-d", strtotime("+1 day")),
            'title' => $this->faker->text(),
            'venue' => $this->faker->text(),
            'partner_agency' => $this->faker->text(),
            'college_id' => 87,
            'department_id' => 292,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 1,
            'report_year' => 2022,
        ];
    }
}

