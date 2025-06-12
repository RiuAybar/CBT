<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasPorAspecto extends Model
{
    protected $table = 'notas_por_aspectos';
    /** @use HasFactory<\Database\Factories\NotasPorAspectoFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'evaluacion_id',
        'escala_evaluativa_id',
        'cantidad_obtenida',
        'puntaje_obtenido',
    ];
}
