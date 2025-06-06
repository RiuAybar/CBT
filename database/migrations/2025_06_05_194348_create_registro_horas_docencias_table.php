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
        Schema::create('registro_horas_docencias', function (Blueprint $table) {
            $table->id();
            $table->string('mes'); // Ej: "FEBRERO"
            $table->unsignedInteger('horasImpartidas'); // Ej: 20
            $table->foreignId('carrera_id')->nullable()->constrained(); // clave foránea a carreras.id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_horas_docencias');
    }
};
