<?php

namespace Database\Factories;

use App\Models\ExpertServiceAcademic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertServiceAcademicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App\Models\ExpertServiceAcademic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $path = '/public/storage/cover.jpg';
        return [
            //
            'classification' => random_int(75,78),
            'nature' => random_int(79,85),
            'from' => date("Y-m-d"),
            'to' => date('Y-m-d', strtotime($Date. ' + 1 days')),
            'publication_or_audio_visual' => $this->faker->title,
            'copyright_no' => 'ABC-123-L8N3',
            'indexing' => random_int(87,93),
            'level' => random_int(94,97),
            'college_id' => 86,
            'department_id' => 292,
            'description' => 'Special Order',
            'document' => new \Symfony\Component\HttpFoundation\File\UploadedFile ($path, null, null, null, null, true)
        ];
    }
}
