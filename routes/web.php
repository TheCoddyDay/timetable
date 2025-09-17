<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimetableController;


Route::get('/', [TimetableController::class, 'index'])->name('timetable.index');

Route::get('/display', [TimetableController::class, 'display'])->name('timetable.display');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protect admin dashboard and all modifying routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TimetableController::class, 'dashboard'])->name('timetable.dashboard');

    Route::post('/add/{day}', [TimetableController::class, 'store'])->name('timetable.store');
    Route::get('/show/{day}/{id}', [TimetableController::class, 'show'])->name('timetable.show');
    Route::get('/edit/{day}/{id}', [TimetableController::class, 'edit'])->name('timetable.edit');
    Route::put('/update/{day}/{id}', [TimetableController::class, 'update'])->name('timetable.update');
    Route::delete('/delete/{day}/{id}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
});
