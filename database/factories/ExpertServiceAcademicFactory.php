<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertServiceAcademicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'classification' => rand(75, 78),
            'nature' => rand(79, 85),
            'from' => date("Y-m-d"),
            'to' => date("Y-m-d", strtotime("+1 day")),
            'publication_or_audio_visual' => $this->faker->word(),
            'copyright_no' => $this->faker->word(),
            'indexing' => rand(87, 93),
            'level' => rand(94, 97),
            'college_id' => 1,
            'department_id' => 2,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 2,
            'report_year' => 2022,
        ];
    }
}

