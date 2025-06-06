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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('grado_id')->constrained()->onDelete('restrict');
             // Primero define la columna grado_id
            // $table->unsignedBigInteger('grado_id');
            // $table->foreign('grado_id')->references('id')->on('grados');

            $table->foreignId('grupo_id')->constrained()->onDelete('restrict');
            // grupo_id con clave foránea sin onDelete restrict
            // $table->unsignedBigInteger('grupo_id');
            // $table->foreign('grupo_id')->references('id')->on('grupos');

            $table->integer('listaNumero')->nullable(); // Número en lista
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
