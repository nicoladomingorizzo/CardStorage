<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nome del pokemon
            $table->string('type');  // Tipo di pokemon
            $table->string('rarity');  // Rarità della carta
            $table->integer('hp')->nullable();  // Punti salute
            $table->decimal('price', 8, 2)->nullable();  // Prezzo stimato
            $table->text('description')->nullable();  // Descrizione
            $table->string('image_path')->nullable();  // Per la foto della carta
            $table->foreignId('expansion_id')->constrained()->onDelete('cascade');  // Espansione della carta

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
