<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Http\Responses\ApiResponse;
use App\Models\EventType;
use App\Models\Label;
use App\Models\Task;
use App\Services\EventService;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    protected EventService $eventService;
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(int $boardId): ApiResponse
    {
        $labels = Label::where('board_id', $boardId)->get();

        return ApiResponse::ok($labels->toArray());
    }

    public function store(LabelRequest $request): ApiResponse
    {
        $label = Label::create([
            'name' => $request->get('name'),
            'color' => $request->get('color'),
            'board_id' => $request->get('board_id'),
        ]);

        return ApiResponse::ok($label->toArray());
    }

    public function update(LabelRequest $request, int $id): ApiResponse
    {
        $label = Label::find($id);
        $label->update([
            'name' => $request->get('name'),
            'color' => $request->get('color'),
            'board_id' => $request->get('board_id'),
        ]);
        $label->save();

        return ApiResponse::ok($label->toArray());
    }

    public function delete(int $id): ApiResponse
    {
        $IntegrationSetting = Label::find($id);
        $IntegrationSetting->delete();

        return ApiResponse::ok();
    }

    public function assign(Request $request, int $taskId): ApiResponse
    {
        $task = Task::findOrFail($taskId);

        $currentLabelIds = $task->labels()->pluck('id')->toArray();
        $newLabelIds = array_values($request->get('label_ids', []));

        $labelsToAdd = array_diff($newLabelIds, $currentLabelIds);
        $labelsToRemove = array_diff($currentLabelIds, $newLabelIds);

        $task->labels()->sync($newLabelIds);

        if (!empty($labelsToAdd)) {
            $this->eventService->createEvent($task->id, $task->user()->id, EventType::TYPE_LABEL_ASSIGNED);
        }

        if (!empty($labelsToRemove)) {
            $this->eventService->createEvent($task->id, $task->user()->id, EventType::TYPE_LABEL_UNASSIGNED);
        }

        $task->load(['labels', 'user']);

        return ApiResponse::ok($task->toArray());
    }
}
