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
            'topic' => $this->faker->randomElement(['REPORTING', 'PROMOTION', 'USER', 'CATEGORY', 'PRODUCT', 'CART']),
            'tag'=> $this->faker->text(8),
            'visible'=> 'NO',
            'enables_at' => null,
            'expires_at' => null,
        ];
    }
}
