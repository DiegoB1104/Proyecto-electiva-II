<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ParkingController;

Route::get('/', [ParkingController::class, 'index'])->name('parking.index');
Route::post('/assign', [ParkingController::class, 'store'])->name('parking.assign');
Route::post('/assign-manual', [ParkingController::class, 'manualAssign'])->name('parking.assign.manual');
Route::post('/release/{id}', [ParkingController::class, 'release'])->name('parking.release');
// Ruta para la búsqueda de placas
Route::get('/parking/search', [ParkingController::class, 'search'])->name('parking.search');

