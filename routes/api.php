<?php

use App\Http\Controllers\Api\CardController;
use Illuminate\Support\Facades\Route;

Route::get('/cards', [CardController::class, 'index']);
