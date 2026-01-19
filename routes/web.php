<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\ExpansionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gallery', function () {
    return view('guest');  // React "Guest"
})->name('guest.gallery');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    Route::resource('admin/cards', CardController::class);
    Route::post('/expansions-store-ajax', [ExpansionController::class, 'store'])->name('expansions.ajax');
});

require __DIR__ . '/auth.php';
