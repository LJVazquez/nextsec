<?php

namespace App\Policies;

use App\Models\Email;
use App\Models\IntelxData;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EmailPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Email $email)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    public function update(User $user, Email $email)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Email  $email
     * @return mixed
     */
    public function delete(User $user, Email $email)
    {
        return $user->id === $email->domain->user->id;
    }

    public function getFile(IntelxData $file)
    {
        // return $file->email->domain->user->id === Auth::id();
    }
}
