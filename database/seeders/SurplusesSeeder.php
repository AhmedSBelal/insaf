<?php

namespace Database\Seeders;

use App\Models\Surplus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurplusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Surplus::factory()->count(100)->create();
    }
}
