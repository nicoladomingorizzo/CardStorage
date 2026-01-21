<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Expansion::create([
            'id' => 1,
            'name' => 'Scarlatto e Violetto',
            // aggiungi altri campi se obbligatori nella tua migration
        ]);

        $this->call([CardsTableSeeder::class]);
    }
}
