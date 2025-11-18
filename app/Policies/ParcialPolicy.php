<?php

namespace App\Policies;

use App\Models\Parcial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ParcialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver parciales');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Parcial $parcial): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('puede agregar parciales');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Parcial $parcial): bool
    {
        return $user->can('puede editar parciales');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Parcial $parcial): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Parcial $parcial): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Parcial $parcial): bool
    {
        return false;
    }
}
