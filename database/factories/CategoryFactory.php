<?php

namespace Database\Factories;

use App\Enums\ImageType;
use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_id' => Admin::inRandomOrder()->value('id') ?? Admin::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }

    public function configure(){
        return $this->afterCreating(function (Category $category) {
            $category->image()->create([
                'url' => 'defaults/images/category.jpeg',
                'type' => ImageType::Cover->value,
            ]);
        });
    }

}
