<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\User;

trait ChecksWorkspacesAccess
{
    public function userHasAccessToWorkspace(int $workspaceId, int $userId): bool
    {
        return User::find($userId)
            ->workspaces()
            ->where('workspace_id', $workspaceId)
            ->exists();
    }
}
