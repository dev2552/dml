<?php

namespace App\Policies;

use App\Models\RegistrarModel;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrarPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isRoot();
    }

    public function all(User $user)
    {
    	 return ($user->isRoot() || $user->isUser() || $user->isManager());
    }

    public function update(User $user,RegistrarModel $registrarModel)
    {
    	return ($user->id == $registrarModel->user->id);
    }

    public function delete(User $user,RegistrarModel $registrarModel)
    {
    	return ($user->id == $registrarModel->user->id);
    }

}
