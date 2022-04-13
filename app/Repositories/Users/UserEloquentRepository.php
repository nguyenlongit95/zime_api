<?php

namespace App\Repositories\Users;

use App\Models\File;
use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\Auth;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{

    /**
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * function Check package by client
     *
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

    /**
     * Repository function Trim name of email
     *
     * @param String $email of user
     * @return mixed|string
     */
    public function trimEmail($email)
    {
        return explode("@", $email)[0];
    }

    /**
     * Repository function check type of package
     *
     * @return mixed
     */
    public function checkTypePackage()
    {
        return Auth::user()->package_id;
    }

    /**
     * Repository function count total files of user was uploaded
     *
     * @return mixed
     */
    public function countTotalFiles(){
        return File::where('user_id', Auth::user()->id)->count();
    }
}
