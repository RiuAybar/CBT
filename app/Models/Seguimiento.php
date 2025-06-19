<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    /** @use HasFactory<\Database\Factories\SeguimientoFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'materia_id',
        'semestre_id',
        'grupo_id',
        'carrera_id',
        'profesor_id',
        'ano',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
    public function Grupos()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function Lista()
    {
        return $this->hasMany(Lista::class, 'seguimiento_id');
    }
}
