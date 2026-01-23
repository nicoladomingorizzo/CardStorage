<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    public function run(): void
    {
        $cards = [
            [
                'name' => 'Pikachu',
                'hp' => 60,
                'type' => 'Elettro',
                'rarity' => 'Comune',
                'price' => 5.5,
                'description' => 'Quando diversi di questi Pokémon si radunano, la loro elettricità può causare tempeste di fulmini.',
                'expansion_id' => 1,
            ],
            [
                'name' => 'Mega Ball',
                'hp' => null,
                'type' => 'Allenatore - Strumento',
                'rarity' => 'Non Comune',
                'price' => 1.2,
                'description' => 'Guarda le prime sette carte del tuo mazzo.',
                'expansion_id' => 1,
            ]
        ];

        foreach ($cards as $card) {
            Card::create($card);
        }
    }
}
