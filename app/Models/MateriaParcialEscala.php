<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriaParcialEscala extends Model
{
    protected $table = 'materia_parcial_escala';

    protected $fillable = [
        'materia_id',
        'parcial_id',
        'escala_evaluativa_id',
        'valor_maximo',
        'porcentaje',
        'activo'
    ];

    public function materia() {
        return $this->belongsTo(Materia::class);
    }

    public function parcial() {
        return $this->belongsTo(Parcial::class);
    }

    public function escala() {
        return $this->belongsTo(EscalaEvaluativa::class, 'escala_evaluativa_id');
    }
}
