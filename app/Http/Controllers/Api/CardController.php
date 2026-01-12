<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;

class CardController extends Controller
{
    public function index()
    {
        // Recuperiamo tutto includendo l'espansione
        $cards = Card::with('expansion')->get();

        return response()->json([
            'success' => true,
            'results' => $cards
        ]);
    }
}
