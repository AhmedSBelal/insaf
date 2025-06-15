<?php

namespace Database\Factories;

use App\Enums\ImageType;
use App\Enums\SurplusStatus;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SurplusFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->value('id') ?? Supplier::factory(),
            'admin_id' => Admin::inRandomOrder()->value('id') ?? Admin::factory(),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'expire_date' => $this->faker->dateTimeBetween('+1 week', '+1 year')->format('Y-m-d'),
            'status' => $this->faker->randomElement(SurplusStatus::values()),
        ];

    }

    public function configure()
    {
        return $this->afterCreating(function ($surplus) {
            $surplus->images()->createMany([
                [
                    'url' => 'defaults/images/cover_surplus.jpeg',
                    'type' => ImageType::Cover->value,
                ],
                [
                    'url' => 'defaults/images/cover_surplus.jpeg',
                    'type' => ImageType::Details->value,
                ],
                [
                    'url' => 'defaults/images/cover_surplus.jpeg',
                    'type' => ImageType::Details->value,
                ],
                [
                    'url' => 'defaults/images/cover_surplus.jpeg',
                    'type' => ImageType::Details->value,
                ],
                [
                    'url' => 'defaults/images/cover_surplus.jpeg',
                    'type' => ImageType::Details->value,
                ],
            ]);
            $surplus->location()->create([
                'physical_location' => 'idko',
            ]);
        });
    }

}
