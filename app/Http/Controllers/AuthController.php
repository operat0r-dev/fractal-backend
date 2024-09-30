<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if (User::where('email', $request->get('email'))->exists()) {
            return ApiResponse::conflict('emailAlreadyExists');
        }

        return ApiResponse::created(
            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ])->toArray()
        );
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->get('email'))->first();

        if (! $user || ! Hash::check($request->get('password'), $user->password)) {
            return ApiResponse::notFound('invalidCredentials');
        }

        $token = JWTAuth::fromUser($user);
        $expiresIn = JWTAuth::factory()->getTTL() * 60;

        return ApiResponse::ok([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn,
        ]);
    }

    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            $expiresIn = JWTAuth::factory()->getTTL() * 60;

            return ApiResponse::ok([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expiresIn' => $expiresIn,
            ]);
        } catch (JWTException $e) {
            return ApiResponse::unauthenticated($e->getMessage());
        }
    }

    public function me()
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return ApiResponse::notFound();
        }

        return ApiResponse::ok($user->toArray());
    }
}
