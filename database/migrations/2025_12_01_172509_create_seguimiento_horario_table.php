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
        Schema::create('seguimiento_horarios', function (Blueprint $table) {
            $table->id();
            // Relación con seguimiento
            $table->foreignId('seguimiento_id')
                  ->constrained('seguimientos')
                  ->onDelete('cascade');

            // Día de la semana (LUNES, MARTES, etc.)
            $table->string('dia', 20);

            // Bloque de horas
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_horarios');
    }
};
