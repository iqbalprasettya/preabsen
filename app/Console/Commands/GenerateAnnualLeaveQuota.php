<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\LeaveQuota;

class GenerateAnnualLeaveQuota extends Command
{
    protected $signature = 'leave:generate-quota {year?}';
    protected $description = 'Generate kuota cuti tahunan untuk semua karyawan';

    public function handle()
    {
        $year = $this->argument('year') ?? now()->year;

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            LeaveQuota::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'year' => $year,
                ],
                [
                    'annual_quota' => 12,
                    'used_quota' => 0,
                    'remaining_quota' => 12,
                ]
            );
            $count++;
        }

        $this->info("Berhasil generate kuota cuti untuk {$count} karyawan.");
    }
}
