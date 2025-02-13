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
        // Validasi parameter bulan dan tahun
        $request->validate([
            'month' => 'nullable|numeric|between:1,12',
            'year' => 'nullable|numeric|min:2000',
            'per_page' => 'nullable|numeric|min:1|max:100' // Tambahan validasi untuk per_page
        ]);

        $query = $request->user()->attendances();

        // Filter berdasarkan bulan dan tahun jika parameter tersedia
        if ($request->month && $request->year) {
            $query->whereYear('check_in', $request->year)
                ->whereMonth('check_in', $request->month);
        }
        // Jika hanya tahun yang tersedia
        else if ($request->year) {
            $query->whereYear('check_in', $request->year);
        }
        // Default: tampilkan bulan ini
        else {
            $query->whereYear('check_in', now()->year)
                ->whereMonth('check_in', now()->month);
        }

        // Jumlah item per halaman (default 10)
        $perPage = $request->per_page ?? 10;

        $attendances = $query->latest()->paginate($perPage)->withQueryString();

        // Transform collection
        $attendances->getCollection()->transform(function ($attendance) {
            $attendance->check_in_photo_url = $attendance->check_in_photo ?
                url('storage/' . $attendance->check_in_photo) : null;
            $attendance->check_out_photo_url = $attendance->check_out_photo ?
                url('storage/' . $attendance->check_out_photo) : null;
            return $attendance;
        });

        // Tambahkan informasi filter ke metadata
        $metadata = [
            'filter' => [
                'month' => $request->month ?? now()->month,
                'year' => $request->year ?? now()->year,
            ],
            'total_records' => $attendances->total(),
            'records_per_page' => $perPage,
            'current_page' => $attendances->currentPage(),
            'total_pages' => $attendances->lastPage(),
        ];

        return response()->json([
            'data' => $attendances->items(),
            'meta' => $metadata,
            'links' => [
                'first' => $attendances->url(1),
                'last' => $attendances->url($attendances->lastPage()),
                'prev' => $attendances->previousPageUrl(),
                'next' => $attendances->nextPageUrl(),
            ]
        ]);
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

    public function summary(Request $request)
    {
        // Validasi parameter bulan dan tahun
        $request->validate([
            'month' => 'nullable|numeric|between:1,12',
            'year' => 'nullable|numeric|min:2000'
        ]);

        // Gunakan bulan dan tahun dari request atau default ke bulan dan tahun sekarang
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // Dapatkan jumlah hari kerja dalam bulan tersebut
        $totalDays = Carbon::create($year, $month)->daysInMonth;

        // Query untuk mendapatkan data kehadiran
        $attendances = $request->user()
            ->attendances()
            ->whereYear('check_in', $year)
            ->whereMonth('check_in', $month)
            ->get();

        // Hitung jumlah masing-masing status
        $summary = [
            'present' => $attendances->whereIn('status', ['present', 'early'])->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'half_day' => $attendances->where('status', 'half_day')->count(),
            'overtime' => $attendances->where('status', 'overtime')->count(),
            'total_days' => $totalDays,
            'total_present' => $attendances->count(),
            'absent' => $attendances->where('status', 'absent')->count()
        ];

        return response()->json([
            'data' => $summary
        ]);
    }
}
