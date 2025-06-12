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
        Schema::create('notas_por_aspectos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluacion_id')->constrained();
            $table->unsignedBigInteger('escala_evaluativa_id')->constrained();
            $table->decimal('cantidad_obtenida', 8, 2);
            $table->decimal('puntaje_obtenido', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_por_aspectos');
    }
};
