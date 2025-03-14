<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/attendance', [AttendanceController::class, 'store']);
Route::get('/attendance', [AttendanceController::class, 'index']);
Route::get('/presents-today', [AttendanceController::class, 'getPresentsToday']);
Route::get('/absents-today', [AttendanceController::class, 'getAbsenteesToday']);
Route::post('/date-present-filter', [AttendanceController::class, 'filterPresentAttendances']);
Route::post('/date-absent-filter', [AttendanceController::class, 'filterAbsentAttendances']);


Route::post('/student', [StudentController::class, 'store']);
Route::get('/student', [StudentController::class, 'index']);
Route::get('/student/edit/{id}', [StudentController::class, 'edit']);
Route::put('/student/update/{id}', [StudentController::class, 'update']);
