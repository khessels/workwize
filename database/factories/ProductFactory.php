<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' =>  $this->faker->name(),
            'active'=> 'YES',
            'price' => $this->faker->numberBetween($min = 5, $max = 200),
        ];
    }
}
