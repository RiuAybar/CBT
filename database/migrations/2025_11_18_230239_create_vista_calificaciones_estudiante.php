<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW vista_calificaciones_estudiante AS
            SELECT 
                e.id AS estudiante_id,
                u.name AS estudiante,
                g.nombre AS grupo,
                gr.nombre AS grado,
                m.nombre AS materia,
                p.nombre AS parcial,
                s.ano AS año_escolar,
                ev.faltas,
                ev.suma,
                ev.calificacion_parcial,
                ev.created_at AS fecha_registro
            FROM evaluaciones ev
            INNER JOIN listas l ON l.id = ev.lista_id
            INNER JOIN estudiantes e ON e.id = l.alumno_id
            INNER JOIN users u ON u.id = e.user_id
            INNER JOIN seguimientos s ON s.id = l.seguimiento_id
            INNER JOIN materias m ON m.id = s.materia_id
            INNER JOIN parciales p ON p.id = ev.parcial_id
            INNER JOIN grupos g ON g.id = e.grupo_id
            INNER JOIN grados gr ON gr.id = g.grado_id;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vista_calificaciones_estudiante");
    }
};
