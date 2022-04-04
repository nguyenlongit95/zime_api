<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return User::class;
    }
}
