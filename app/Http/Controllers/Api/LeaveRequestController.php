<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        // Ambil kuota cuti tahunan
        $currentYear = now()->year;
        $leaveQuota = $request->user()->leaveQuotas()
            ->where('year', $currentYear)
            ->first();

        // Hitung statistik cuti
        $leaveStats = [
            'annual_quota' => $leaveQuota ? $leaveQuota->annual_quota : 0,
            'used_quota' => $leaveQuota ? $leaveQuota->used_quota : 0,
            'remaining_quota' => $leaveQuota ? $leaveQuota->remaining_quota : 0,
            'leave_count' => [
                'total' => $request->user()->leaveRequests()->count(),
                'pending' => $request->user()->leaveRequests()->where('status', 'pending')->count(),
                'approved' => $request->user()->leaveRequests()->where('status', 'approved')->count(),
                'rejected' => $request->user()->leaveRequests()->where('status', 'rejected')->count(),
            ],
            'leave_by_type' => [
                'annual' => $request->user()->leaveRequests()->where('type', 'annual')->count(),
                'sick' => $request->user()->leaveRequests()->where('type', 'sick')->count(),
                'important' => $request->user()->leaveRequests()->where('type', 'important')->count(),
                'other' => $request->user()->leaveRequests()->where('type', 'other')->count(),
            ]
        ];

        $leaveRequests = $request->user()
            ->leaveRequests()
            ->latest()
            ->paginate(10);

        $leaveRequests->getCollection()->transform(function ($item) {
            $item->attachment_url = $item->attachment
                ? url('storage/' . $item->attachment)
                : null;
            return $item;
        });

        return response()->json([
            'statistics' => $leaveStats,
            'data' => $leaveRequests
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:annual,sick,important,other',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'description' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048'
            ], [
                'type.required' => 'Tipe cuti wajib diisi',
                'type.in' => 'Tipe cuti tidak valid',
                'start_date.required' => 'Tanggal mulai wajib diisi',
                'start_date.date' => 'Format tanggal mulai tidak valid',
                'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya',
                'end_date.required' => 'Tanggal selesai wajib diisi',
                'end_date.date' => 'Format tanggal selesai tidak valid',
                'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai',
                'description.required' => 'Deskripsi wajib diisi',
                'description.string' => 'Format deskripsi tidak valid',
                'attachment.file' => 'Lampiran harus berupa file',
                'attachment.mimes' => 'Format file tidak didukung. Gunakan: jpeg, png, jpg, pdf, doc, atau docx',
                'attachment.max' => 'Ukuran file maksimal 2MB'
            ]);

            // Cek kuota jika tipe cuti adalah annual
            if ($request->type === 'annual') {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $durationInDays = $endDate->diffInDays($startDate) + 1;

                $quota = $request->user()->leaveQuotas()
                    ->where('year', now()->year)
                    ->first();

                if (!$quota) {
                    return response()->json([
                        'message' => 'Kuota cuti tahunan belum diatur',
                    ], 422);
                }

                if ($quota->remaining_quota < $durationInDays) {
                    return response()->json([
                        'message' => 'Sisa kuota cuti tidak mencukupi',
                        'remaining_quota' => $quota->remaining_quota,
                        'requested_days' => $durationInDays
                    ], 422);
                }
            }

            $attachment = null;
            if ($request->hasFile('attachment')) {
                $fileName = 'leave-request_' . uniqid();
                $extension = $request->file('attachment')->getClientOriginalExtension();
                $attachment = $request->file('attachment')->storeAs('leave-attachments', $fileName . '.' . $extension, 'public');
            }

            $leaveRequest = $request->user()->leaveRequests()->create([
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'attachment' => $attachment,
                'status' => 'pending'
            ]);

            $leaveRequest->attachment_url = $attachment ? url('storage/' . $attachment) : null;

            return response()->json([
                'message' => 'Permohonan cuti berhasil diajukan',
                'leave_request' => $leaveRequest
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses permintaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $leaveRequest = $request->user()
            ->leaveRequests()
            ->findOrFail($id);

        $leaveRequest->attachment_url = $leaveRequest->attachment
            ? url('storage/' . $leaveRequest->attachment)
            : null;

        return response()->json($leaveRequest);
    }
}
