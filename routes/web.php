<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PasswordSetupController;

Route::get('/', function () {
    return view('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('auth/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('auth/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/auth/password-setup', [PasswordSetupController::class, 'showPasswordSetupForm'])->name('password.setup');
Route::post('/auth/password-setup', [PasswordSetupController::class, 'updatePassword'])->name('password.update');

// Rutas de Superadmin
Route::prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/registro', [SuperadminController::class, 'showRegistrationForm'])->name('superadmin.registro');
    Route::post('/registro', [SuperadminController::class, 'registerNewUser'])->name('superadmin.registro.submit');
    Route::get('/create', [SuperadminController::class, 'create'])->name('superadmin.create');
    Route::post('/store', [SuperadminController::class, 'store'])->name('superadmin.store');
    Route::delete('/tasks/{id}', [SuperadminController::class, 'destroy'])->name('superadmin.delete');
    Route::get('/tasks/{id}/edit', [SuperadminController::class, 'edit'])->name('superadmin.edit');
    Route::put('/tasks/{id}', [SuperadminController::class, 'update'])->name('superadmin.update');
    Route::match(['get', 'post'], '/task/{taskId}/comment', [SuperadminController::class, 'addComment'])->name('superadmin.addComment');
    Route::post('/task/{taskId}/upload-attachment', [SuperadminController::class, 'uploadAttachment'])->name('superadmin.uploadAttachment');
    Route::delete('/task/{taskId}/delete-attachment', [SuperadminController::class, 'deleteAttachment'])->name('superadmin.deleteAttachment');
});

// Rutas de usuario
Route::prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/tasks/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/tasks/{id}', [UserController::class, 'viewTask'])->name('user.task');
    Route::get('/tasks/{userId}', [UserController::class, 'getUserTasks'])->name('user.tasks');
    Route::match(['get', 'post'], '/task/{taskId}/comment', [UserController::class, 'addComment'])->name('user.addComment');
    Route::post('/task/{taskId}/upload-attachment', [UserController::class, 'uploadAttachment'])->name('user.uploadAttachment');
    Route::match(['post', 'delete'],'/task/{taskId}/delete-attachment', [UserController::class, 'deleteAttachment'])->name('user.deleteAttachment');

});
