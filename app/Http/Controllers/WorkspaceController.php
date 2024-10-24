<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceService;
use App\Traits\ChecksWorkspacesAccess;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    use ChecksWorkspacesAccess;
    private WorkspaceService $workspaceService;

    public function __construct(
        WorkspaceService $workspaceService
    ) {
        $this->workspaceService = $workspaceService;
    }

    public function store(Request $request): ApiResponse
    {
        $user = Auth::user();
        $name = $request->get('name');

        $workspace = $this->workspaceService->createWorkspace($name, $user);

        return ApiResponse::ok(
            [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'description' => $workspace->description,
                'current' => $workspace->pivot->current,
            ]
        );
    }

    public function update(Request $request, int $id): ApiResponse
    {
        $workspace = Workspace::find($id);

        $workspace->update($request->only(['name', 'description']));

        return ApiResponse::ok($workspace->toArray());
    }

    public function getUserWorkspaces(): ApiResponse
    {
        $user = Auth::user();

        $workspaces = $user->workspaces->map(function ($workspace) {
            return [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'current' => $workspace->pivot->current,
                'description' => $workspace->description,
            ];
        });

        return ApiResponse::ok($workspaces->toArray());
    }

    public function getWorkspaceUsers(int $id): ApiResponse
    {
        $users = User::whereHas('workspaces', fn ($q) => $q->where('workspace_id', $id))->get();

        return ApiResponse::ok($users->toArray());
    }

    public function setUserWorkspace(Request $request): ApiResponse
    {
        $user = Auth::user();

        $user->workspaces()->updateExistingPivot($user->workspaces->pluck('id'), ['current' => false]);
        $user->workspaces()->updateExistingPivot($request->get('id'), ['current' => true]);

        return ApiResponse::ok();
    }

    public function getOne(Request $request, int $id): ApiResponse
    {
        try {
            $userId = Auth::id();
            
            if (! $this->userHasAccessToWorkspace($id, $userId)) {
                return ApiResponse::forbidden();
            }

            $workspace = Workspace::with(['boards', 'users'])->findOrFail($id);

            return ApiResponse::ok($workspace->toArray());
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound();
        }
    }
}
