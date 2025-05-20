<?php

namespace Database\Seeders\assets;

use App\Models\People\Admin\Admin;
use App\Models\People\Employee\Employee;
use App\Models\People\Supervisor\Supervisor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $lestariBranch = DB::table('branches')->where('email', 'lestari@branch.com')->first();
        $kompakBranch = DB::table('branches')->where('email', 'kompak@branch.com')->first();

        $password  = Hash::make('admin123'); 
        $password1 = Hash::make('supervisor123'); 
        $password2 = Hash::make('petugas123'); 

        $adminUser = User::create([
            'email'             => 'admin@gmail.com',
            'password'          => $password,
            'email_verified_at' => now(),     
        ]);
        
        Admin::create([
            'user_id' => $adminUser->id,
            'name'    => 'Admin Utama',
            'telepon' => '08123456789',
            'foto'    => 'default.jpg',
        ]);
        
        
        $supervisorUser = User::create([
            'email'             => 'supervisor@gmail.com',
            'password'          => $password1,
            'email_verified_at' => now(),
            'branch_id'         => $lestariBranch->branchId,  
        ]);

        Supervisor::create([
            'user_id' => $supervisorUser->id,
            'name'    => 'supervisor mantap',
            'telepon' => '08123456789',
            'foto'    => 'default.jpg',
        ]);
        
        $petugasUser = User::create([
            'email'             => 'petugas@gmail.com',
            'password'          => $password2,
            'email_verified_at' => now(),
            'branch_id'         => $kompakBranch->branchId,  
        ]);

        Employee::create([
            'user_id'    => $petugasUser->id,
            'name'       => 'petugas mantap',
            'telepon'    => '08123456789',
            'foto'       => 'default.jpg',
            'address'    => 'jalan doang ga jadian',
            'birthdate'  => '1990-01-01', 
            'hire_date'  => '2025-04-15', 
            'salary'     => 5000000,      
            'gender'     => 0,
            'status'     => 1      
        ]);
        

        $adminRole      = Role::where('name', 'admin')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $petugasRole    = Role::where('name', 'petugas')->first();

        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }

        if ($supervisorRole) {
            $supervisorUser->assignRole($supervisorRole);
        }

        if ($petugasRole) {
            $petugasUser->assignRole($petugasRole);
        }

        $this->command->info('Users created and roles assigned!');
    }
}
