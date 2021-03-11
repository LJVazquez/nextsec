<?php

namespace App\Policies;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    public function author(User $user, Domain $domain)
    {
        return $user->id === $domain->user_id;
    }
}
