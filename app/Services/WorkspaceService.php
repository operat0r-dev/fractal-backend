<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceService
{
    public function createWorkspace(Request $request, User $user)
    {
        $workspace = Workspace::create([
            'name' => $request->get('name'),
        ]);

        $user->workspaces()->attach($workspace->id);

        $workspaceWithPivot = $user->workspaces()->withPivot('current')->find($workspace->id);

        return $workspaceWithPivot;
    }
}
