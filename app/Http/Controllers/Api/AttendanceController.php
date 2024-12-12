<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = $request->user()
            ->attendances()
            ->latest()
            ->paginate(10);
            
        return response()->json($attendances);
    }

    public function today(Request $request)
    {
        $attendance = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();
            
        return response()->json([
            'attendance' => $attendance
        ]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:2048'
        ]);

        // Simpan foto
        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('attendance-photos', 'public');
        }

        $attendance = $request->user()->attendances()->create([
            'check_in' => now(),
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photo,
            'status' => 'present' // Logika status bisa ditambahkan
        ]);

        return response()->json([
            'message' => 'Check in berhasil',
            'attendance' => $attendance
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:2048'
        ]);

        $attendance = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'Anda belum check in hari ini'
            ], 400);
        }

        // Simpan foto
        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('attendance-photos', 'public');
        }

        $attendance->update([
            'check_out' => now(),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photo
        ]);

        return response()->json([
            'message' => 'Check out berhasil',
            'attendance' => $attendance
        ]);
    }
}
