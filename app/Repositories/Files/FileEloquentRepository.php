<?php

namespace App\Repositories\Files;

use App\Models\File;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\Auth;

class FileEloquentRepository extends EloquentRepository implements FileRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return File::class;
    }

    /**
     * SQL function listFileOfUser
     *
     * @return mixed
     */
    public function listFileOfUser()
    {
        return File::where('user_id', Auth::user()->id)->get();
    }

    public function listFileOfOtherUser($user){
        return File::where('user_id', $user->id)->orderBy('id')->paginate(4);
    }

}
