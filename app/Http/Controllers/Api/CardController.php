<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with(['expansion', 'images'])->get()->map(function ($card) {
            // Colori Rarità
            $card->rarity_color = match ($card->rarity) {
                'Ultra Rara' => '#dc3545',  // bg-danger
                'Rara Segreta' => '#ffc107',  // bg-warning
                'Illustrazione Speciale' => '#0d6efd',  // bg-primary
                'Rara Holo' => '#198754',  // bg-success
                'Comune' => '#6c757d',  // bg-secondary
                default => '#212529',  // bg-dark
            };

            // Colori Tipo
            $tcgTypes = [
                'Fuoco' => ['bg' => '#ff0000', 'text' => 'white'],
                'Acqua' => ['bg' => '#0077ff', 'text' => 'white'],
                'Erba' => ['bg' => '#22aa22', 'text' => 'white'],
                'Elettro' => ['bg' => '#ffff99', 'text' => 'black'],
                'Psico' => ['bg' => '#aa22aa', 'text' => 'white'],
                'Lotta' => ['bg' => '#884422', 'text' => 'white'],
                'Oscurità' => ['bg' => '#333333', 'text' => 'white'],
                'Acciaio' => ['bg' => '#778899', 'text' => 'white'],
                'Drago' => ['bg' => '#ccaa00', 'text' => 'white'],
                'Incolore' => ['bg' => '#dddddd', 'text' => 'black'],
                'Folletto' => ['bg' => '#ff99cc', 'text' => 'black'],
            ];

            $typeStyle = $tcgTypes[$card->type] ?? ['bg' => '#6c757d', 'text' => 'white'];

            $card->type_bg_color = $typeStyle['bg'];
            $card->type_text_color = $typeStyle['text'];

            $card->rarity_text_color = ($card->rarity === 'Rara Segreta') ? 'black' : 'white';

            return $card;
        });

        return response()->json([
            'success' => true,
            'results' => $cards
        ]);
    }
}
