<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AdminController;
Route::get('/', [CarController::class, 'index'])->name('index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Alleen index is openbaar, alles eronder vereist auth
Route::middleware('auth')->group(function () {
    Route::get('/offers.offerStep1', function () {
        return view('offers.offerStep1');
    })->name('offers.offerStep1');

    Route::get('/offers/addcar', [CarController::class, 'create_step1'])->name('offers.addcar');
    Route::get('/offers/addcar2/{license_plate}', [CarController::class, 'create_step2'])->name('offercar.step2');
    Route::post('/offers/store', [CarController::class, 'store'])->name('offercar.store');
    // Step 3: review / select tags for a saved car

    Route::get('/offers/addcar3/{car}', [CarController::class, 'create_step4'])->name('offercar.step3');
    Route::post('/offers/store-tags', [CarController::class, 'store_tags'])->name('offercar.store_tags');

    
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');

Route::get('/owncars', [CarController::class, 'owncars'])->name('owncars');
Route::get('/owncars/{car}/tags', [CarController::class, 'editTags'])->name('owncars.tags.edit');
Route::post('/owncars/{car}/tags', [CarController::class, 'updateTags'])->name('owncars.tags.update');
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
