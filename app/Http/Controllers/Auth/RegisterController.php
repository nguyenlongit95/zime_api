<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string',
            'c_password' => 'required|same:password',
            'phone'=>'unique:users',
        ]);
        if ($validator->fails()){
            return response()->json([
                'status'=>'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }
        $user = new User([
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'phone'=>$request->phone,
        ]);
        $user->save();
        $storage_file = explode("@",$request->email);
        Storage::makeDirectory('/public/'.array_shift($storage_file));
        return response()->json([
            'status' => 'success',
            'message' => 'User register successfully.',
        ]);
    }
}
