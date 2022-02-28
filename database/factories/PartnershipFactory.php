<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PartnershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'moa_code' => $this->faker->randomNumber(4, true),
            'collab_nature' => rand(131, 137),
            'partnership_type' => rand(139, 148),
            'deliverable' => $this->faker->word(),
            'name_of_partner' => $this->faker->word(),
            'title_of_partnership' => $this->faker->word(),
            'beneficiaries' => $this->faker->word(),
            'start_date' => date("Y-m-d"),
            'end_date' => date("Y-m-d", strtotime("+1 day")),
            'level' => rand(158, 162),
            'name_of_contact_person' => $this->faker->name.', '.$this->faker->name.', '.$this->faker->name,
            'address_of_contact_person' => $this->faker->text(),
            'telephone_number' => $this->faker->phoneNumber(),
            'college_id' => 86,
            'department_id' => 292,
            'description' => $this->faker->word(),
            'user_id' => rand(3, 4)
            // User::all()->random()->id,
        ];
    }
}
