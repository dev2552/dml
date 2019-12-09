<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestServerPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isRoot();
    }
}
