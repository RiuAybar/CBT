<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SeguimientoHorario extends Model
{
    // protected $table = 'seguimiento_horario';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'seguimiento_id',
        'dia',
        'hora_inicio',
        'hora_fin',
    ];
    
}
