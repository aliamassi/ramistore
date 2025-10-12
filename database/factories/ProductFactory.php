<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

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
    public function definition(): array
    {

        $fakerEn = $this->faker;
        $fakerAr = FakerFactory::create('ar_SA'); // Arabic faker
        return [
            'category_id' => Category::factory(),
            'name' => $fakerEn->unique()->word(),
//            'name' => [
//                'en' => $fakerEn->unique()->word(),
//                'ar' => $fakerAr->unique()->word(), // Example: "هاتف ذكي"
//            ],
            'description' => fake()->text(),
            'type' => fake()->randomElement(['simple', 'variable']),
            'price' => fake()->randomFloat(2, 10, 1000),
        ];
    }


}
