<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::where('is_active', true)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'holidays' => $holidays->map(function ($holiday) {
                return [
                    'id' => $holiday->id,
                    'date' => $holiday->date->format('Y-m-d'),
                    'name' => $holiday->name,
                    'description' => $holiday->description,
                    'is_active' => (bool) $holiday->is_active,
                ];
            })
        ]);
    }
}
