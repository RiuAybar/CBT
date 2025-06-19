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
        Schema::create('materia_parcial_escala', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('parcial_id')->constrained('parciales')->onDelete('cascade');
            $table->foreignId('escala_evaluativa_id')->constrained('escalas_evaluativas')->onDelete('cascade');

             // Campos adicionales:
            $table->decimal('valor_maximo', 5, 2)->nullable();  // Ej. 10.0, 15.0
            $table->decimal('porcentaje', 5, 2)->nullable();    // Ej. 25%, 50%
            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Evitar duplicados
            $table->unique(['materia_id', 'parcial_id', 'escala_evaluativa_id'], 'unique_materia_parcial_escala');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_parcial_escala');
    }
};
