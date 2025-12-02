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
        Schema::create('escuela', function (Blueprint $table) {
            $table->id();
            // Información general
            $table->string('nombre_escuela');
            $table->string('direccion')->nullable();
            $table->string('departamento')->nullable();
            $table->string('turno')->nullable(); // Matutino, Vespertino, etc.
            $table->string('nivel')->nullable(); // Preescolar, Primaria, etc.
            $table->string('clave_trabajo')->nullable();
            $table->string('numero_cct')->nullable(); // "No. DE C.R.E.S.E." (renombrado para compatibilidad)
            // Ubicación
            $table->string('zona_escolar')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('localidad_colonia')->nullable();
            $table->string('municipio')->nullable();
            // Contacto
            $table->string('telefono')->nullable();
            // Personal
            $table->string('docente')->nullable();
            $table->string('subdirector_escolar')->nullable();
            $table->string('director_escolar')->nullable();
            $table->string('secretario_escolar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escuela');
    }
};
