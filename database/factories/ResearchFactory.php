<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Research;
use App\Models\User;

class ResearchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Research::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = auth()->id();
        return [
            //
            'classification' => rand(1, 3),
            'category' => rand(4, 5),
            'agenda' => rand(6, 10),
            'title' => $this->faker->sentence(),
            'researchers' => $this->faker->name.', '.$this->faker->name.', '.$this->faker->name,
            'keywords' => $this->faker->word().', '.$this->faker->word().', '.$this->faker->word().', '.
                    $this->faker->word().', '.$this->faker->word(),
            'nature_of_involvement' => rand(11, 13),
            'research_type' => rand(14, 22),
            'funding_type' => rand(23, 25),
            'funding_amount' => $this->faker->randomFloat(2),
            'funding_agency' => $this->faker->text(),
            'status' => rand(26, 27),
            'start_date' => date("Y-m-d"),
            'target_date' => date("Y-m-d", strtotime("+1 day")),
            'college_id' => 87,
            'department_id' => 292,
            'description' => $this->faker->word(),
            'user_id' => 2,
            'report_quarter' => 2,
            'report_year' => 2022,
        ];
    }
}
