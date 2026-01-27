<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name,['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Brand $brand): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Brand $brand): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Brand $brand): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Brand $brand): bool
    {
        return in_array($user->role->name, ['admin-product', 'admin-head']);
    }
}
