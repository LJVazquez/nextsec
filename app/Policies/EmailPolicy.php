<?php

namespace App\Policies;

use App\Models\Email;
use App\Models\IntelxData;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailPolicy
{
    use HandlesAuthorization;

    public function author(User $user, Email $email)
    {
        return $user->id === $email->user_id;
    }
}
