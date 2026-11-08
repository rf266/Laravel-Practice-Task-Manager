<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AvatarUploadController;
use App\Http\Controllers\DocumentUploadController;



Route::get('/register', [AuthController::class, 'showRegister' ])->name('register');
Route::post('/register', [Authcontroller::class, 'register'])->middleware('throttle:register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');


Route::middleware(['auth.custom', 'throttle:tasks'])->group(function() {
    Route::get('/', function () {
        return redirect()->route('tasks.index');
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    
    //these are protected as they can only be accessed when logged in
    Route::resource('tasks', TaskController::class);

    Route::get('/profile/{user}',[UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/edit',[UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}',[UserProfileController::class, 'update'])->name('profile.update');

    Route::get('/avatar/{user}/upload', [AvatarUploadController::class, 'create'])->name('avatars.create');
    Route::post('/avatar/{user}', [AvatarUploadController::class, 'store'])->middleware('throttle:uploads')->name('avatars.store');
    Route::delete('/avatar/{user}', [AvatarUploadController::class, 'destroy'])->name('avatars.destroy');

    Route::get('/document', [DocumentUploadController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentUploadController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentUploadController::class, 'store'])->middleware('throttle:uploads')->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentUploadController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentUploadController::class, 'destroy'])->name('documents.destroy');

    });

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
Route::post('/email/resend', [Authcontroller::class, 'resendVerificationEmail'])->name('email.resend');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordReset'])->middleware('throttle:password-reset')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-reset')->name('password.update');
