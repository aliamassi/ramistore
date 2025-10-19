<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Constant>
 */
class ConstantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'key'   => $this->faker->unique()->slug(2),
            'value' => $this->faker->word(),
            'name' => $this->faker->randomElement(['business','system']),
        ];
    }
}
