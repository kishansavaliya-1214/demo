<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/students', [StudentAPIController::class, 'index']);
    Route::post('/students', [StudentAPIController::class, 'store']);
    Route::get('/student/{id}', [StudentAPIController::class, 'show']);
    Route::put('/student/{student}', [StudentAPIController::class, 'update']);
    Route::delete('/student/{id}', [StudentAPIController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
