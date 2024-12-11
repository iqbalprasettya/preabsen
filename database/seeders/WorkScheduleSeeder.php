<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'name' => 'Jadwal Normal',
                'check_in_start' => '07:00',
                'check_in_end' => '08:30',
                'check_out_start' => '16:00',
                'check_out_end' => '17:30'
            ],
            [
                'name' => 'Jadwal Shift Pagi',
                'check_in_start' => '06:00',
                'check_in_end' => '07:30',
                'check_out_start' => '14:00',
                'check_out_end' => '15:30'
            ],
            [
                'name' => 'Jadwal Shift Siang',
                'check_in_start' => '14:00',
                'check_in_end' => '15:30',
                'check_out_start' => '22:00',
                'check_out_end' => '23:30'
            ]
        ];

        foreach ($schedules as $schedule) {
            WorkSchedule::create($schedule);
        }
    }
} 