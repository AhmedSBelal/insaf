<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "charity_id" => \App\Models\Charity::inRandomOrder()->first()->id,
            "payment_id" => \App\Models\Payment::inRandomOrder()->first()->id,
            "total_price" => $this->faker->numberBetween(100, 1000),
            "status" => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
