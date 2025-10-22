<?php

namespace App\Policies;

use App\Models\User;

class PlayerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, User $player): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, User $player): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, User $player): bool
    {
        return $user->hasRole('admin');
    }
}
