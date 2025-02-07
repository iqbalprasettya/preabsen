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
        $user = $request->user()->load('officeLocation');
        $attendance = $user->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($attendance) {
            $attendance->check_in_photo_url = $attendance->check_in_photo ?
                url('storage/' . $attendance->check_in_photo) : null;
            $attendance->check_out_photo_url = $attendance->check_out_photo ?
                url('storage/' . $attendance->check_out_photo) : null;
        }

        return response()->json([
            'attendance' => $attendance,
            'office_location' => $user->officeLocation
        ]);
    }

    public function checkIn(Request $request)
    {
        // Cek apakah sudah ada absensi hari ini
        $existingAttendance = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'message' => 'Anda sudah melakukan check in hari ini'
            ], 400);
        }

        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:2048'
        ]);

        // Cek jadwal kerja user
        $workSchedule = $request->user()->workSchedule;
        if (!$workSchedule) {
            return response()->json([
                'message' => 'Jadwal kerja belum diatur'
            ], 400);
        }

        // Tentukan status check in
        $now = now();
        $checkInStart = Carbon::today()->setTimeFromTimeString($workSchedule->check_in_start);

        $status = 'present';
        if ($now->isAfter($checkInStart)) {
            $status = 'late';
        } else if ($now->isBefore($checkInStart)) {
            $status = 'early';
        }

        // Simpan foto
        $photo = null;
        if ($request->hasFile('photo')) {
            $fileName = 'attendance_' . uniqid();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $photo = $request->file('photo')->storeAs('attendance-photos', $fileName . '.' . $extension, 'public');
        }

        $attendance = $request->user()->attendances()->create([
            'check_in' => $now,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photo,
            'status' => $status
        ]);

        $attendance->check_in_photo_url = $photo ? url('storage/' . $photo) : null;

        return response()->json([
            'message' => 'Check in berhasil',
            'attendance' => $attendance
        ]);
    }

    public function checkOut(Request $request)
    {
        $attendance = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'Anda belum check in hari ini'
            ], 400);
        }

        // Cek apakah sudah check out
        if ($attendance->check_out) {
            return response()->json([
                'message' => 'Anda sudah melakukan check out hari ini'
            ], 400);
        }

        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:2048'
        ]);

        $workSchedule = $request->user()->workSchedule;
        if (!$workSchedule) {
            return response()->json([
                'message' => 'Jadwal kerja belum diatur'
            ], 400);
        }

        try {
            $now = now();
            $checkOutStart = Carbon::today()->setTimeFromTimeString($workSchedule->check_out_start);
            $checkOutEnd = Carbon::today()->setTimeFromTimeString($workSchedule->check_out_end);

            // Update status berdasarkan waktu check out
            $status = $attendance->status; // Pertahankan status check in

            if ($now->isBetween($checkOutStart, $checkOutEnd)) {
                $status = 'present';
            } else if ($now->isBefore($checkOutStart)) {
                $status = 'half_day';
            } else if ($now->isAfter($checkOutEnd)) {
                $status = 'overtime';
            }

            // Simpan foto
            $photo = null;
            if ($request->hasFile('photo')) {
                $fileName = 'attendance_' . uniqid();
                $extension = $request->file('photo')->getClientOriginalExtension();
                $photo = $request->file('photo')->storeAs('attendance-photos', $fileName . '.' . $extension, 'public');
            }

            $attendance->update([
                'check_out' => $now,
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
