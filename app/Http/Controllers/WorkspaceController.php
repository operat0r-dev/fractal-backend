<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Workspace;
use App\Services\WorkspaceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    private WorkspaceService $workspaceService;

    public function __construct(
        WorkspaceService $workspaceService
    ) {
        $this->workspaceService = $workspaceService;
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $workspace = $this->workspaceService->createWorkspace($request, $user);

        return ApiResponse::ok(
            [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'current' => $workspace->pivot->current,
            ]
        );
    }

    public function update(Request $request, int $id)
    {
        $workspace = Workspace::find($id);

        $workspace->update([
            'name' => $request->get('name'),
        ]);

        return ApiResponse::ok($workspace->toArray());
    }

    public function getUserWorkspaces()
    {
        $user = Auth::user();

        $workspaces = $user->workspaces->map(function ($workspace) {
            return [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'current' => $workspace->pivot->current,
            ];
        });

        return ApiResponse::ok($workspaces->toArray());
    }

    public function setUserWorkspace(Request $request)
    {
        $user = Auth::user();

        $user->workspaces()->updateExistingPivot($user->workspaces->pluck('id'), ['current' => false]);
        $user->workspaces()->updateExistingPivot($request->get('id'), ['current' => true]);

        return ApiResponse::ok();
    }

    public function getOne(Request $request, int $id)
    {
        try {
            $workspace = Workspace::with('boards')->find($id);

            return ApiResponse::ok($workspace->toArray());
        } catch (Exception $e) {
        }
    }
}
