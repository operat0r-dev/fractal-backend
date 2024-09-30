<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return ApiResponse::notFound();
        }

        $user->update([
            'name' => $request->get('name'),
        ]);

        return ApiResponse::ok($user->toArray());
    }
}
