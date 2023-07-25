<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerification;
use Illuminate\Support\Facades\Route;

// API Routes for authentication
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])
    ->middleware([TokenVerification::class]);
// User Logout
Route::get('/logout',[UserController::class,'UserLogout']);    

// Route for todo
Route::get('/api/todos', [TodoController::class, 'index'])->middleware([TokenVerification::class]);
Route::get('/api/todos/{id}', [TodoController::class, 'show'])->middleware([TokenVerification::class]);
Route::post('/api/todos', [TodoController::class, 'store'])->middleware([TokenVerification::class]);
Route::put('/api/todos/{id}', [TodoController::class, 'update'])->middleware([TokenVerification::class]);
Route::delete('/api/todos/{id}', [TodoController::class, 'delete'])->middleware([TokenVerification::class]);
