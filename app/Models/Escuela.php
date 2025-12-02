<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escuela extends Model
{
    protected $table = 'escuela';

    /** @use HasFactory<\Database\Factories\MateriaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre_escuela',
        'direccion',
        'departamento',
        'turno',
        'nivel',
        'clave_trabajo',
        'numero_cct',
        'zona_escolar',
        'domicilio',
        'localidad_colonia',
        'municipio',
        'telefono',
        'docente',
        'subdirector_escolar',
        'director_escolar',
        'secretario_escolar',
    ];
}
