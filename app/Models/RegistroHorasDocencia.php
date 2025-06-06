<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroHorasDocencia extends Model
{
    /** @use HasFactory<\Database\Factories\RegistroHorasDocenciaFactory> */
    use HasFactory;

    protected $fillable = [
        'mes',
        'horasImpartidas',
        'carrera_id'
    ];

    public function setMesAttribute($value)
    {
        $this->attributes['mes'] = ucfirst(strtolower($value));
    }
    public static function esMesValido(string $mes): bool
    {
        $mesesValidos = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        return in_array(ucfirst(strtolower($mes)), $mesesValidos);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
