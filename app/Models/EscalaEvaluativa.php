<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalaEvaluativa extends Model
{
    protected $table = 'escalas_evaluativas';
    /** @use HasFactory<\Database\Factories\EscalaEvaluativaFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'abreviatura',
    ];
}
