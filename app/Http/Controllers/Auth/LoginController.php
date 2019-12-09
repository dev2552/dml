<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   
    use AuthenticatesUsers;


    protected $redirectTo = '/home';



    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'),['active'=>1]);
    }
}
