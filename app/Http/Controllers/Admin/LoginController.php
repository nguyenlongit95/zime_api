<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt([
            'email' => $request -> email,
            'password' => $request -> password,
        ])){
            return redirect()->route('admin.dashboard');
        }
        Session::flash('error','Incorrect username or password');
        return redirect()->back();
    }
}
