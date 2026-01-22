<?php

use App\Http\Controllers\Api\CardController;
use Illuminate\Support\Facades\Route;

// Rotta principale React
Route::get('/cards', [CardController::class, 'index']);

// Dettaglio singolo
Route::get('/cards/{id}', [CardController::class, 'show']);

// Eliminazione
Route::delete('/cards/{id}', [CardController::class, 'destroy']);
