<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;   
use App\Models\Product;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
