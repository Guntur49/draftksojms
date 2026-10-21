<?php

namespace Database\Factories;

use App\Models\MasterLetter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MasterLetter>
 */
class MasterLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'category' => 'Perjanjian Kerja Sama',
            'description' => fake()->sentence(),
            'body' => 'Perjanjian antara {{nama_jms}} dan {{nama_rumah_sakit}}.',
        ];
    }
}
