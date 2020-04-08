<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ApiResponseException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function getUser()
    {
        return auth()->user();
    }
}
