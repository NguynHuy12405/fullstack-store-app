<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function reply(User $user, Review $review): bool
    {
        return in_array($user->role->name, [
            'admin-user',
            'admin-head',
        ]);
    }
}

