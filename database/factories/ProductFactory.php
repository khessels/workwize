<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' =>  $this->faker->name(),
            'active'=> 'YES',
            'price' => $this->faker->numberBetween($min = 5, $max = 200),
            'stock' => $this->faker->numberBetween($min = 0, $max = 10),
        ];
    }
}
