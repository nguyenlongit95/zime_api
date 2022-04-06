<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
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
