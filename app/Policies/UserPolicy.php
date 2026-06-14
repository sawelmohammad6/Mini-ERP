<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

/**
 * Why Policy?
 * -----------
 * Centralizes authorization logic instead of scattering raw role checks
 * across controllers, middleware, and Blade files.  Policies integrate
 * natively with Laravel's Gate facade, @can Blade directives, and
 * can: middleware.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin->value;
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin->value;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin->value;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin->value;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin->value;
    }
}
