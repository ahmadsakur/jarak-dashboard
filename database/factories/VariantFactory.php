<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //create variant factory associated with product
            'variant_name' => $this->faker->name,
            'price' => $this->faker->randomFloat(0, 10000, 32000),
            'product_id'=> function () {
                return Product::inRandomOrder()->first()->id;
            },


        ];
    }
}
