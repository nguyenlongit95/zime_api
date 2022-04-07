<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Controller checkPackage by client
     */
    public function checkPackage(){
        if (!isset(Auth::user()->package_id)){
            return app()->make(ResponseHelper::class)->notFound('Package not found!');
        }else{
            return app()->make(ResponseHelper::class)->success('You have used Package '.Auth::user()->package_id);
        }
    }

}
