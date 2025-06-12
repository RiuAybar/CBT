<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    /** @use HasFactory<\Database\Factories\EvaluacionFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lista_id',
        'parcial_id',
        'faltas',
        'suma',
        'calificacion_parcial',
    ];

    public function notasPorAspectos()
    {
        return $this->hasMany(NotasPorAspecto::class);
    }
}
