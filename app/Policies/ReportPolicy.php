<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin->value;
    }

    public function view(User $user): bool
    {
        return $user->role === UserRole::Admin->value;
    }
}
