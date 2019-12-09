<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isRoot();
    }

    public function show(User $user)
    {
        return  $user->isRoot() || $user->isManager() || $user->isUser() ;
    }

    public function filterBy(User $user)
    {
        return $user->isRoot() || $user->isManager() || $user->isUser();
    }
}
