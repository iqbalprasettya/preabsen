<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeLocation;

class OfficeLocationSeeder extends Seeder
{
    public function run(): void
    {
        OfficeLocation::create([
            'name' => 'Kantor Pusat',
            'address' => 'Jl. MH Thamrin No.1, Jakarta Pusat',
            'latitude' => -6.16562763,
            'longitude' => 106.82379663,
            'radius' => 50, // radius dalam meter
        ]);
    }
} 