<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PasswordSetupController;
use App\Http\Controllers\TaskController;
Route::get('/', function () {
    return view('login');
});

// Rutas Login

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Olvide Contraseña
Route::get('auth/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('auth/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rutas para restablecimiento de contraseña

Route::get('/password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//Rutas Configurar contraseña primer ingreso

Route::get('/auth/password-setup', [PasswordSetupController::class, 'showPasswordSetupForm'])->name('password.setup');
Route::post('/auth/password-setup', [PasswordSetupController::class, 'updatePassword'])->name('password.update');


//super Admin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
Route::get('/superadmin/registro', [SuperadminController::class, 'showRegistrationForm'])->name('superadmin.registro');
Route::post('/superadmin/registro', [SuperadminController::class, 'registerNewUser'])->name('superadmin.registro.submit');
Route::get('/superadmin/create', [SuperadminController::class, 'create'])->name('superadmin.create');
Route::post('/superadmin/store', [SuperadminController::class, 'store'])->name('superadmin.store');


//Rutas de Tareas
Route::post('/superadmin/tasks', [SuperadminController::class, 'store'])->name('superadmin.store');
Route::delete('/superadmin/tasks/{id}', [SuperadminController::class, 'destroy'])->name('superadmin.delete');
Route::get('/superadmin/tasks/{id}/edit', [SuperadminController::class, 'edit'])->name('superadmin.edit');


//User
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
