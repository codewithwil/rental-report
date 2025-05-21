<?php

namespace Database\Seeders\assets;

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('brands')->insert([
            [
                'name' => 'Honda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toyota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lexus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Daihatsu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Suzuki',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nissan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hyundai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mercedes-Benz',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BMW',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Audi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Volkswagen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Porsche',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lamborghini',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rolls-Royce',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ford',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chevrolet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wuling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Renault',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jaguar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
