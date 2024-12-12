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

        return response()->json($leaveRequests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:annual,sick,important,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        $attachment = null;
        if ($request->hasFile('attachment')) {
            $fileName = uniqid() . '_' . $request->file('attachment')->getClientOriginalName();
            $attachment = $request->file('attachment')->storeAs('leave-attachments', $fileName, 'public');
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
