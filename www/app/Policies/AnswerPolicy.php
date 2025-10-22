<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;

class AnswerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Answer $answer): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Answer $answer): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Answer $answer): bool
    {
        return $user->hasRole('admin');
    }
}
