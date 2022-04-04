<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;

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
}
