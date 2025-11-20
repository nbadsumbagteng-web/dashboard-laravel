<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// Routes untuk CRUD operations
Route::get('/sow-data', [DashboardController::class, 'getSowData'])->name('sow-data.index');
Route::get('/sow-data/{id}/edit', [DashboardController::class, 'editSowData'])->name('sow-data.edit');
Route::post('/sow-data', [DashboardController::class, 'storeSowData'])->name('sow-data.store');
Route::put('/sow-data/{id}', [DashboardController::class, 'updateSowData'])->name('sow-data.update');
Route::delete('/sow-data/{id}', [DashboardController::class, 'deleteSowData'])->name('sow-data.destroy');
Route::post('/export-data', [DashboardController::class, 'exportData'])->name('data.export');
Route::post('/import-data', [DashboardController::class, 'importData'])->name('data.import');
Route::get('/realtime-data', [DashboardController::class, 'getRealTimeData'])->name('realtime.data');
Route::get('/network-performance', [DashboardController::class, 'getNetworkPerformance'])->name('network.performance');