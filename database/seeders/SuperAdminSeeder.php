<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone_number' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'position' => 'Super Admin',
            'employee_id' => 'SA001'
        ]);

        $superAdmin->assignRole('super_admin');
    }
} 