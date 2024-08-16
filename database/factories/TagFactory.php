<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'topic' => $this->faker->name(),
            'tag'=> $this->faker->name(),
            'enables_at' => null,
            'expires_at' => null,
        ];
    }
}
