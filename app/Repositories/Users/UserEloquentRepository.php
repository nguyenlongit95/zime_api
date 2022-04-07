<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * function Check package by client
     * @param $user -> User is logged in
     */
    public function checkPackage($user)
    {
        if (!isset($user->package_id)) {
            return false;
        } else {
            return true;
        }
    }
}
