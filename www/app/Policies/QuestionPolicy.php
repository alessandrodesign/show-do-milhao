<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Question $question): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Question $question): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Question $question): bool
    {
        return $user->hasRole('admin');
    }
}
