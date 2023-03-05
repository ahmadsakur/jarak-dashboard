<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //Create Product Factory associated with Category
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'image' => '1676141660.png',
            'imageUrl' => 'http://jarak-dashboard.test/storage/images/products/1676141660.png',
            'isSoldOut' => false,

        ];
    }
}
