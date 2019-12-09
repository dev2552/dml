<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IpPolicy
{
    use HandlesAuthorization;


    public function index(User $user)
    {
        return $user->isRoot() or $user->isManager() or $user->isUser() ;
    }

    public function create(User $user)
    {
        return $user->isRoot();
    }

}
