<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Controller login function by client
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return app()->make(ResponseHelper::class)->validation(trans('validation.login_failed'));
        }

        $credentials = request(['email','password']);

        if (!Auth::attempt($credentials)){
            return response()->json([
                'status'=>'fails',
                'message' => 'Unauthorized',
            ],401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User login successfully!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
