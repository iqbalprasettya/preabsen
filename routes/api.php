<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\InformationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

        // Profile
        Route::get('profile', [ProfileController::class, 'show']);
        Route::post('profile', [ProfileController::class, 'update']);

        // Attendance
        Route::get('attendances', [AttendanceController::class, 'index']);
        Route::get('attendances/today', [AttendanceController::class, 'today']);
        Route::post('attendances/check-in', [AttendanceController::class, 'checkIn']);
        Route::post('attendances/check-out', [AttendanceController::class, 'checkOut']);
        Route::get('attendances/summary', [AttendanceController::class, 'summary']);

        // Leave Request
        Route::get('leave-requests', [LeaveRequestController::class, 'index']);
        Route::post('leave-requests', [LeaveRequestController::class, 'store']);
        Route::get('leave-requests/{id}', [LeaveRequestController::class, 'show']);

        // Holiday
        Route::get('holidays', [HolidayController::class, 'index']);

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);

        // Information
        Route::get('information', [InformationController::class, 'index']);
        Route::get('information/{id}', [InformationController::class, 'show']);
    });
});
