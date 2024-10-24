<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Column;
use App\Models\EventType;
use App\Models\Task;
use App\Services\EventService;
use App\Traits\ChecksWorkspacesAccess;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ChecksWorkspacesAccess;

    protected EventService $eventService;
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function store(TaskRequest $request): ApiResponse
    {
        $columnId = $request->get('column_id');
        $column = Column::find($columnId);
        $workspaceId = $column->board()->workspace_id;

        $userId = $request->user()->id;

        if (!$this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden('You do not have access to this workspace.');
        }

        $task = Task::create([
            'title' => $request->get('title'),
            'column_id' => $request->get('column_id'),
            'seq' => $request->get('seq'),
        ]);

        $task->load('labels');

        return ApiResponse::created($task->toArray());
    }

    public function update(TaskRequest $request, int $id): ApiResponse
    {
        $task = Task::with('column.board')->find($id); 
    
        $workspaceId = $task->column->board->workspace_id;
        $userId = $request->user()->id;
    
        if (!$this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden();
        }
    
        $updatedData = $request->only(['column_id', 'seq', 'title', 'description']);
        $eventsToLog = [];
    
        if ($task->column->id !== $updatedData['column_id']) {
            $eventsToLog[] = EventType::MOVED;
        }
    
        if ($task->title !== $updatedData['title']) {
            $eventsToLog[] = EventType::TYPE_TITLE_CHANGED;
        }
    
        if ($task->description !== $updatedData['description']) {
            $eventsToLog[] = EventType::TYPE_DESCRIPTION_CHANGED;
        }
    
        $task->update($updatedData);
    
        foreach ($eventsToLog as $eventType) {
            $this->eventService->createEvent($task->id, $userId, $eventType);
        }
    
        return ApiResponse::ok();
    }

    public function assignUser(Request $request, int $id): ApiResponse
    {
        $task = Task::with('column.board')->find($id);

        if (!$task || !$task->column || !$task->column->board) {
            return ApiResponse::notFound();
        }

        $workspaceId = $task->column->board->workspace_id;
        $userId = $request->user()->id;

        if (!$this->userHasAccessToWorkspace($workspaceId, $userId)) {
            return ApiResponse::forbidden();
        }

        $task->update($request->only(['user_id']));

        if (null === $userId) {
            $this->eventService->createEvent($task->id, $request->user()->id, EventType::TYPE_USER_UNASSIGNED);
        } else {
            $this->eventService->createEvent($task->id, $request->user()->id, EventType::TYPE_USER_ASSIGNED);
        }

        $task->load(['user', 'labels']);

        return ApiResponse::ok($task->toArray());
    }
}
