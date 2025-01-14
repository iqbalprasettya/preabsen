<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check-timezone', function () {
    dd([
        'PHP Default Timezone' => date_default_timezone_get(),
        'Laravel Config Timezone' => config('app.timezone'),
        'Current Time' => now()->format('Y-m-d H:i:s'),
    ]);
});
