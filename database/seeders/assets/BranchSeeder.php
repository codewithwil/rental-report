<?php

namespace Database\Seeders\assets;

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};

class BranchSeeder extends Seeder
{
    public function run(): void
    {

        $company = DB::table('companies')->where('name', 'Rentalku')->first();
        DB::table('branches')->insert([
            [
                'user_id' => null,
                'company_id' => $company->companyId,
                'address' => 'Jalan Kiara Condong No. 456, Bandung, Jawa Barat',
                'email' => 'lestari@branch.com',
                'operationalHours' => '08:00 - 17:00',
                'phone' => '0227654321',
                'ltd' => -6.925000,  
                'lng' => 107.610000, 
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => null,
                'company_id' => $company->companyId,
                'address' => 'Jalan Soekarno Hatta No. 789, Bandung, Jawa Barat',
                'email' => 'kompak@branch.com',
                'operationalHours' => '08:00 - 17:00',
                'phone' => '0229876543',
                'ltd' => -6.940000,  
                'lng' => 107.650000, 
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
