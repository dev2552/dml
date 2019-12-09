<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return ( $user->isRoot() || $user->isManager() || $user->isUser() );
    }

    public function create(User $user)
    {
        return $user->isRoot();
    }

    public function update()
    {
        return $user->isRoot();
    }

    public function destroy(User $user)
    {
        return $user->isRoot();
    }
}
