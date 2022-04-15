<?php

namespace App\Repositories\Files;

use App\Models\File;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * SQL function list file of user
     *
     * @return mixed
     */
    public function listFileOfUser()
    {
        return File::where('user_id', Auth::user()->id)->get();
    }

    /**
     * SQL function list file of others user
     *
     * @param $user
     * @return mixed
     */
    public function listFileOfOtherUser($user){
        return File::where('user_id', $user->id)->orderBy('id')->paginate(4);
    }

    /**
     * SQl function total files were uploaded by all users
     *
     * @return int
     */
    public function totalFiles(){
        return DB::table('files')->count();
    }
}
