<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    public function before($user, $ability)
    {
        if($user->isAdmin() === true)
        {
            return true;
        }
    }

    public function access($user)
    {
        return false;
    }

    public function list($user)
    {
        return false;
    }

    public function create($user)
    {
        return false;
    }

    public function edit($user, $ability)
    {
        return false;
    }

    public function update($user)
    {
        return false;
    }

    public function view($user, $ability)
    {
        return false;
    }

    public function delete($user, $ability)
    {
        return false;
    }
}