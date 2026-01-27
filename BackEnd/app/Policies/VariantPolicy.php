<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Variant;
use App\Models\User;

class VariantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Variant $variant): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Variant $variant): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Variant $variant): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Variant $variant): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Variant $variant): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }
}
