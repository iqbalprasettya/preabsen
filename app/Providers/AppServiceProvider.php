<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\LeaveQuota;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        date_default_timezone_set('Asia/Jakarta');
        config(['app.timezone' => 'Asia/Jakarta']);

        // Auto generate kuota cuti untuk tahun baru
        $currentYear = Carbon::now()->year;

        // Cek setiap request jika ada user yang belum punya kuota tahun ini
        if (!app()->runningInConsole()) {
            User::chunk(100, function ($users) use ($currentYear) {
                foreach ($users as $user) {
                    LeaveQuota::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'year' => $currentYear,
                        ],
                        [
                            'annual_quota' => 12,
                            'used_quota' => 0,
                            'remaining_quota' => 12,
                        ]
                    );
                }
            });
        }
    }
}
