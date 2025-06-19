<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpParser\Node\Expr\FuncCall;

class Grupo extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'grado_id',
    ];

    public function grados()
    {
        return $this->hasMany(Grado::class, 'grado_id');
    }
}
