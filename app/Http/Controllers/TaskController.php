<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Column;
use App\Models\Task;
use App\Traits\ChecksWorkspacesAccess;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ChecksWorkspacesAccess;

    public function store(TaskRequest $request): ApiResponse
    {
        $columnId = $request->get('column_id');
        $column = Column::find($columnId);
        $workspaceId = $column->board()->workspace_id;

        $userId = $request->user()->id;

        if (! $this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden('You do not have access to this workspace.');
        }

        $task = Task::create($request->only(['title', 'column_id', 'seq']));

        return ApiResponse::created($task->toArray());
    }

    public function update(TaskRequest $request, int $id): ApiResponse
    {
        $task = Task::find($id);

        $workspaceId = $task->column()->board()->workspace_id;
        $userId = $request->user()->id;

        if (! $this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden();
        }

        $task->update($request->only(['column_id', 'seq']));

        return ApiResponse::ok();
    }

    public function getOne(int $id): ApiResponse
    {
        try {
            $task = Task::findOrFail($id);

            return ApiResponse::ok($task->toArray());
        } catch (\Exception $e) {
            return ApiResponse::notFound($e->getMessage());
        }
    }

    public function assignUser(Request $request, int $id): ApiResponse
    {
        $task = Task::find($id);

        $workspaceId = $task->column()->board()->workspace_id;
        $userId = $request->user()->id;

        if (! $this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden();
        }

        $task->update($request->only(['user_id']));

        return ApiResponse::ok($task->toArray());
    }
}
