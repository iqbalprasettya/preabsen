<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        $departements = [
            [
                'name' => 'Human Resources',
                'description' => 'Departemen yang mengelola sumber daya manusia'
            ],
            [
                'name' => 'Information Technology',
                'description' => 'Departemen yang mengelola teknologi informasi'
            ],
            [
                'name' => 'Finance',
                'description' => 'Departemen yang mengelola keuangan'
            ],
            [
                'name' => 'Marketing',
                'description' => 'Departemen yang mengelola pemasaran'
            ],
            [
                'name' => 'Operations',
                'description' => 'Departemen yang mengelola operasional'
            ]
        ];

        foreach ($departements as $departement) {
            Departement::create($departement);
        }
    }
} 