<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
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
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
    public function cambiarEstatus(User $authUser, User $targetUser)
    {
        $rolObjetivo = $targetUser->getRoleNames()->first();

        $permisos = [
            'estudiante'    => 'puede desabilitar estudiantes',
            'profesor'      => 'puede desabilitar profesores',
            'orientador'    => 'puede desabilitar orientadores',
            'admin' => 'puede desabilitar administradores',
        ];

        if (!isset($permisos[$rolObjetivo])) {
            return false;
        }

        return $authUser->can($permisos[$rolObjetivo]);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function Escuela(User $user): bool
    {
        return $user->can('ver datos de mi escuela');
    }
    /**
     * Determine whether the user can view any models.
     */
    public function EscuelaUpdate(User $user): bool
    {
        return $user->can('puede editar datos de mi escuela');
    }
}
