<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(Request $request)
    {
        if (Auth::attempt($request->only("email","password"))) {
            return $this->success('Authorized', [
                'token' => $request->user()->createToken('API Token')->plainTextToken
            ]);
        }

        return $this->error('Login and Password not match or user not found', [], 403);
    }
}
