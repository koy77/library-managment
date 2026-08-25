<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id' => Author::factory(),
            'title' => $this->faker->unique()->words(3, true),
            'publication_year' => $this->faker->numberBetween(1800, date('Y')),
            'isbn' => $this->faker->unique()->isbn13(),
            'total_copies' => $this->faker->numberBetween(1, 8),
        ];
    }
}
