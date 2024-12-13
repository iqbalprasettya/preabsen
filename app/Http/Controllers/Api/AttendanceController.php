<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = $request->user()
            ->attendances()
            ->latest()
            ->paginate(10);
            
        $attendances->getCollection()->transform(function ($attendance) {
            $attendance->check_in_photo_url = $attendance->check_in_photo ? 
                url('storage/' . $attendance->check_in_photo) : null;
            $attendance->check_out_photo_url = $attendance->check_out_photo ? 
                url('storage/' . $attendance->check_out_photo) : null;
            return $attendance;
        });

        return response()->json($attendances);
    }

    public function today(Request $request)
    {
        $attendance = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();
            
        if ($attendance) {
            $attendance->check_in_photo_url = $attendance->check_in_photo ? 
                url('storage/' . $attendance->check_in_photo) : null;
            $attendance->check_out_photo_url = $attendance->check_out_photo ? 
                url('storage/' . $attendance->check_out_photo) : null;
        }

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
            $fileName = 'attendance_' . uniqid();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $photo = $request->file('photo')->storeAs('attendance-photos', $fileName . '.' . $extension, 'public');
        }

        $attendance = $request->user()->attendances()->create([
            'check_in' => now(),
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photo,
        ]);

        $attendance->check_in_photo_url = $photo ? url('storage/' . $photo) : null;

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

        // Ambil jadwal kerja user
        $workSchedule = $request->user()->workSchedule;

        if (!$workSchedule) {
            return response()->json([
                'message' => 'Jadwal kerja belum diatur'
            ], 400);
        }
        
        // Tentukan status berdasarkan waktu check in dan check out
        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = now();
        
        try {
            // Konversi jadwal ke timestamp hari ini
            $checkInStart = Carbon::today()->setTimeFromTimeString($workSchedule->check_in_start);
            $checkInEnd = Carbon::today()->setTimeFromTimeString($workSchedule->check_in_end);
            $checkOutStart = Carbon::today()->setTimeFromTimeString($workSchedule->check_out_start);
            $checkOutEnd = Carbon::today()->setTimeFromTimeString($workSchedule->check_out_end);
            
            // Tentukan status
            $status = 'absent'; // Default status
            
            // Cek status berdasarkan check in dan check out
            if ($checkInTime->between($checkInStart, $checkInEnd)) {
                if ($checkOutTime->between($checkOutStart, $checkOutEnd)) {
                    $status = 'present'; // Tepat waktu
                } else if ($checkOutTime->isBefore($checkOutStart)) {
                    $status = 'early'; // Pulang lebih awal
                }
            } else {
                if ($checkInTime->isAfter($checkInEnd)) {
                    $status = 'late'; // Masuk terlambat
                }
            }

            // Simpan foto
            $photo = null;
            if ($request->hasFile('photo')) {
                $fileName = 'attendance_' . uniqid();
                $extension = $request->file('photo')->getClientOriginalExtension();
                $photo = $request->file('photo')->storeAs('attendance-photos', $fileName . '.' . $extension, 'public');
            }

            $attendance->update([
                'check_out' => $checkOutTime,
                'check_out_latitude' => $request->latitude,
                'check_out_longitude' => $request->longitude,
                'check_out_photo' => $photo,
                'status' => $status
            ]);

            $attendance->check_in_photo_url = $attendance->check_in_photo ? 
                url('storage/' . $attendance->check_in_photo) : null;
            $attendance->check_out_photo_url = $photo ? 
                url('storage/' . $photo) : null;

            return response()->json([
                'message' => 'Check out berhasil',
                'attendance' => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses check out',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
