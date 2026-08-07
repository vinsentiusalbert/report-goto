<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DailyEventReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventReportController;
use App\Http\Controllers\UploadReportGotoController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/event-report', EventReportController::class)->name('event-report');
    Route::get('/daily-event-report', DailyEventReportController::class)->name('daily-event-report');
    Route::get('/upload-report-goto', [UploadReportGotoController::class, 'create'])->name('upload-report-goto.create');
    Route::post('/upload-report-goto', [UploadReportGotoController::class, 'store'])->name('upload-report-goto.store');
    Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('password.edit');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('password.update');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
