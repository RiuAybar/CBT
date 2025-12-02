<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'orientador_id',
        'ciclo',
        'ano',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
    public function Grupos()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
    public function Lista()
    {
        return $this->hasMany(Lista::class, 'seguimiento_id');
    }
    public function RegistroHorasDocencia()
    {
        return $this->hasMany(RegistroHorasDocencia::class, 'carrera_id', 'carrera_id')
            ->where('materia_id', $this->materia_id);
        // ->latest()->limit(5);
    }
    public function Profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
    public function Orientador()
    {
        return $this->belongsTo(User::class, 'orientador_id');
    }
    public function Carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }
    public function Semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
    public function SeguimientoHorario()
    {
        return $this->hasMany(SeguimientoHorario::class, 'seguimiento_id');
    }
}
