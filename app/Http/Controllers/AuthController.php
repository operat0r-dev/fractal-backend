<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        if (User::where("email", $request->get("email"))->exists()) {
            return ApiResponse::conflict('emailAlreadyExists');
        }
        
        return ApiResponse::ok(
            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
            ])
        );
    }

    public function login(Request $request) {
        $user = User::where("email", $request->get("email"))->first();

        if (!$user || !Hash::check($request->get("password"), $user->password)) {
            return ApiResponse::notFound('invalidCredentials');
        }

        $token = JWTAuth::fromUser($user);
        $expiresIn = JWTAuth::factory()->getTTL() * 60;

        return ApiResponse::ok([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn
        ]);
    }
}
