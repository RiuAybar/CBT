<?php

namespace App\Policies;

use App\Models\Lista;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ListaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver listas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lista $lista): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lista $lista): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lista $lista): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lista $lista): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lista $lista): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function agregarEstudiantelista(User $user): bool
    {
        return $user->can('puede agregar alumno listas');
    }

    public function buscarParciales(User $user): bool
    {
        return $user->can('ver parciales en lista');
    }

    public function buscarEscalasAsignadas(User $user): bool
    {
        return $user->can('ver escalas asignadas en lista');
    }

    public function asignarEscalaLista(User $user): bool
    {
        return $user->can('puede agregar escala en lista');
    }

    public function deleteMateriaParcialEscala(User $user): bool
    {
        return $user->can('puede eliminar materia parcial escala');
    }

    public function guardarNotasPorAspecto(User $user, Lista $lista): bool
    {
        // Validaciones básicas
        if (!$user || !$lista || !$lista->seguimiento) {
            return false;
        }
        // Si es estudiantes → nunca puede guardar
        if ($user->hasRole('estudiante')) {
            return false;
        }
        // Si es profesor → solo si el seguimiento le pertenece
        if ($user->hasRole('profesor')) {
            return $lista->seguimiento->profesor_id === $user->id;
        }
        // Otros roles (admin, coordinador, etc.) → siempre pueden
        return true;
    }
}
