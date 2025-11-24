<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\DriverOrderController;



Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/client/orders', [ClientOrderController::class, 'index']);
    Route::post('/client/orders', [ClientOrderController::class, 'store']);
    Route::get('/client/orders/{id}', [ClientOrderController::class, 'show']);
});
Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::get('/driver/orders', [DriverOrderController::class, 'index']);
    Route::patch('/driver/orders/{id}/status', [DriverOrderController::class, 'updateStatus']);
});
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/orders', [AdminOrderController::class, 'index']);
    Route::post('/admin/orders/{id}/assign-driver', [AdminOrderController::class, 'assignDriver']);
    Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel']);
});