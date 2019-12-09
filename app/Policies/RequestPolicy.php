<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
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

    public function managerAndRoot(User $user)
    {
    	return ($user->isRoot() || $user->isManager());
    }
}
