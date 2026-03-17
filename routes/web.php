<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AdminController;
Route::get('/', [CarController::class, 'index'])->name('index');

// Alleen index is openbaar, alles eronder vereist auth
Route::middleware('auth')->group(function () {
    Route::get('/offers.offerStep1', function () {
        return view('offers.offerStep1');
    })->name('offers.offerStep1');

    Route::get('/offers/addcar', [CarController::class, 'create_step1'])->name('offers.addcar');
    Route::get('/offers/addcar2/{license_plate}', [CarController::class, 'create_step2'])->name('offercar.step2');
    Route::post('/offers/store', [CarController::class, 'store'])->name('offercar.store');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');

Route::get('/owncars', [CarController::class, 'owncars'])->name('owncars');
Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
Route::get('/cars/{car}/pdf', [CarController::class, 'exportPdf'])->middleware('auth')->name('cars.pdf');

Route::get('/dashboard', function () {
    return redirect()->route('index'); 
})->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
