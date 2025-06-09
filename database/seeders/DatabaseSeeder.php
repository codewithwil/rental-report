<?php

namespace Database\Seeders;

use Database\{
    Seeders\assets\UserSeeder,
    Seeders\assets\RolesSeeder,
    Seeders\assets\BranchSeeder,
    Seeders\assets\CategorySeeder,
    Seeders\assets\CompanySeeder,
    Seeders\assets\BrandSeeder,
    Seeders\assets\RulesSeeder
};

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $this->call([
                CompanySeeder::class,  
                BranchSeeder::class,  
                RolesSeeder::class,  
                UserSeeder::class,  
                CategorySeeder::class,  
                BrandSeeder::class,  
                RulesSeeder::class,  
            ]);
                DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }
}
