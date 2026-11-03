<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountApprovalController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/overview');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/overview', [OverviewController::class, 'index'])->name('overview');
    Route::get('/officers', [OfficerController::class, 'index'])->name('officers.index');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('admin')->group(function () {
        Route::resource('officers', OfficerController::class)->except(['index', 'show']);
        Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
        Route::get('/admin/accounts', [AccountApprovalController::class, 'index'])->name('admin.accounts');
        Route::post('/admin/accounts/{user}/approve', [AccountApprovalController::class, 'approve'])->name('admin.accounts.approve');
        Route::post('/admin/accounts/{user}/reject', [AccountApprovalController::class, 'reject'])->name('admin.accounts.reject');
        Route::post('/admin/accounts/{user}/toggle', [AccountApprovalController::class, 'toggle'])->name('admin.accounts.toggle');
        Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
    });
});
