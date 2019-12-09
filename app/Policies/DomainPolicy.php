<?php

namespace App\Policies;

use App\Models\DomainModel;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        return $user->isRoot();
    }

    public function all(User $user)
    {
        return ($user->isRoot() || $user->isUser() || $user->isManager());
    }

    public function update(User $user,DomainModel $domainModel)
    {
        return ($user->id == $domainModel->user->id);
    }

    public function delete(User $user,DomainModel $domainModel)
    {
        return ($user->id == $domainModel->user->id);
    }
}
