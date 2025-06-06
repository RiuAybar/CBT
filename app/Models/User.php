<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // protected $dateFormat = 'Y-m-d\TH:i:s.v';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'estatus',
        'telefono',
        'domicilio',
        'localidadColonia',
        'password',
    ];

    protected $appends = ['rol', 'RolId'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    public function image()
    {
        return $this->morphOne(\App\Models\Image::class, 'imageable');
    }

    public function getRolAttribute()
    {
        $rol = $this->roles->first()?->name;
        return $rol ? ucfirst(strtolower($rol)) : null;
    }
    public function getRolIdAttribute()
    {
        return $this->roles->first()?->id ?? null;
    }
}
