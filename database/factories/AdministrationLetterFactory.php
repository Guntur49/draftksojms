<?php

namespace Database\Factories;

use App\Models\AdministrationLetter;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdministrationLetter>
 */
class AdministrationLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::factory(),
            'master_letter_id' => null,
            'document_name' => fake()->sentence(3, false),
            'document_number' => fake()->unique()->numerify('JMS/PKS/2026/####'),
            'status' => 'draft',
            'pdf_path' => null,
            'variables' => null,
            'content_snapshot' => null,
        ];
    }
}
