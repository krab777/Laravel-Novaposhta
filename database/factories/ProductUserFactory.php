<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\ProductUser;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductUser>
 */
class ProductUserFactory extends Factory
{
    protected $model = ProductUser::class;

    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 2000),
            'user_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
