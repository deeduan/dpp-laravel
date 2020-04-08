<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'login']);
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = auth()->attempt($credentials)) {
            return [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => auth()->factory()->getTTL() * 60
            ];
        }

        return ['error' => 'Unauthorized', 'code' => 401];
    }



}
