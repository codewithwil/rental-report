<?php

namespace Database\Seeders\assets;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('categories')->insert([
            [
                'name' => 'SUV',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MPV',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crossover',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hatchback',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sedan',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sport Sedan',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Convertible',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Station Wagon',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Off road',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pickup Truck & Mobil Double Cabin',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elektrik',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hybrid',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LCGC',
                'type' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
