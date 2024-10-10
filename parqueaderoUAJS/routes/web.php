<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ParkingController;

Route::get('/', [ParkingController::class, 'index'])->name('parking.index');

// Ruta para asignar automáticamente
Route::post('/asignar', [ParkingController::class, 'store'])->name('parking.store');

// Ruta para asignar manualmente
Route::post('/asignar-manual', [ParkingController::class, 'manualAssign'])->name('parking.manualAssign');

// Ruta para liberar un puesto
Route::post('/liberar/{id}', [ParkingController::class, 'release'])->name('parking.release');

// Ruta para generar el reporte en PDF
Route::get('/reporte', [ParkingController::class, 'generateReport'])->name('parking.report');

// Ruta para buscar una placa
Route::get('/buscar', [ParkingController::class, 'search'])->name('parking.search');


