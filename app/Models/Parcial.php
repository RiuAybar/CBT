<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcial extends Model
{
    protected $table = 'parciales';
    /** @use HasFactory<\Database\Factories\ParcialFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
    ];

    public function escalasPorMateria()
    {
        return $this->belongsToMany(EscalaEvaluativa::class, 'materia_parcial_escala')
            ->withPivot('materia_id')
            ->withTimestamps();
    }
}
