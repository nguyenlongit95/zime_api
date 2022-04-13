<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Init global var
     *
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Controller function register for users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(Request $request) {
        //Validate data
        $validator = Validator::make($request->all(), [
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string',
            'c_password' => 'required|same:password',
            'phone'=>'unique:users',
        ]);
        if ($validator->fails()) {
            return app()->make(ResponseHelper::class)->validation($validator->errors()->toArray());
        }

        $param = $request->all();
        try {
            // Create new account
            $this->userRepository->create($param);
            // Make new Directory for new user by email
            Storage::makeDirectory('/public/' . $this->userRepository->trimEmail($request->email));
            return app()->make(ResponseHelper::class)->success();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }
}
