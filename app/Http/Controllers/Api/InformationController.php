<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Information;
use Carbon\Carbon;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        // Validasi parameter bulan dan tahun
        $request->validate([
            'month' => 'nullable|numeric|between:1,12',
            'year' => 'nullable|numeric|min:2000',
            'per_page' => 'nullable|numeric|min:1|max:100',
            'category' => 'nullable|in:policy,event,announcement,news',
            'status' => 'nullable|in:published,draft'
        ]);

        $query = Information::with('creator');

        // Filter berdasarkan bulan dan tahun jika parameter tersedia
        if ($request->month && $request->year) {
            $query->whereYear('created_at', $request->year)
                ->whereMonth('created_at', $request->month);
        }
        // Jika hanya tahun yang tersedia
        else if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        // Filter berdasarkan kategori
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Jumlah item per halaman (default 10)
        $perPage = $request->per_page ?? 10;

        $information = $query->latest()->paginate($perPage);

        return response()->json([
            'status' => 'success', 
            'message' => 'Data informasi berhasil diambil',
            'data' => $information,
            'meta' => [
                'current_page' => $information->currentPage(),
                'from' => $information->firstItem(),
                'last_page' => $information->lastPage(),
                'per_page' => $information->perPage(),
                'to' => $information->lastItem(),
                'total' => $information->total(),
                'filters' => [
                    'month' => $request->month,
                    'year' => $request->year,
                    'category' => $request->category,
                    'status' => $request->status
                ]
            ]
        ]);
    }

    public function show($id)
    {
        $information = Information::with('creator')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail informasi berhasil diambil',
            'data' => $information
        ]);
    }
}
