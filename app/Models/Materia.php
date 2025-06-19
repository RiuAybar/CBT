<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materia extends Model
{
    /** @use HasFactory<\Database\Factories\MateriaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
    ];

    public function escalasPorParcial()
    {
        return $this->belongsToMany(EscalaEvaluativa::class, 'materia_parcial_escala')
            ->withPivot('parcial_id')
            ->withTimestamps();
    }
}
