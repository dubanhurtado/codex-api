<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            // dna_hash: Nuestra huella única. Indexada para búsquedas ultrarrápidas.
            $table->string('dna_hash')->unique(); 
            // Guardamos el JSON original por si necesitamos auditoría
            $table->json('content');
            // El resultado del análisis de Elowen
            $table->boolean('has_clue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};
