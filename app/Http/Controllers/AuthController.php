<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::where("email", $request->get("email"))->exists();

        if ($user) {
            return ApiResponse::conflict('User with this email already exists');
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

        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        if (!Hash::check($request->get("password"), $user->password)) {
            return ApiResponse::notFound('Invalid credentials');
        }

        return ApiResponse::ok($user->toArray());
    }
}
