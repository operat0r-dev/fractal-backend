<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Http\Responses\ApiResponse;
use App\Mail\WorkspaceInvitation;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class UserController extends Controller
{
    public function update(Request $request): ApiResponse
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

    public function getUserByEmail(Request $request, string $email): ApiResponse
    {
        $user = User::where('email', $email)->first();

        if (! $user instanceof User) {
            return ApiResponse::notFound();
        }

        return ApiResponse::ok($user->toArray());
    }

    public function inviteUserToWorkspace(MailRequest $request, int $workspaceId): ApiResponse
    {
        $workspace = Workspace::find($workspaceId);

        if (! $workspace) {
            return ApiResponse::notFound();
        }

        $user = User::where('email', $request->get('email'))->first();
        $workspace->users()->attach($user->id); 
        $link = url("/workspace/{$workspace->id}");
        Mail::to($user->email)->send(new WorkspaceInvitation($workspace->name, $user->email, $link));
        
        return ApiResponse::ok([
            'message' => 'Invitation sent successfully!',
        ]);
    }
}
