<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Label;
use App\Models\LabelTask;
use App\Models\Task;

class LabelController extends Controller
{
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

    public function assign(int $taskId, int $labelId): ApiResponse
    {
        $task = Task::findOrFail($taskId);
        $label = Label::findOrFail($labelId);

        $assigned = LabelTask::create([
            'task_id' => $task->id,
            'label_id' => $label->id,
        ]);
        $assigned->save();

        return ApiResponse::ok();
    }

    public function unassign(int $taskId, int $labelId): ApiResponse
    {
        $assigned = LabelTask::where('task_id', $taskId)
            ->where('label_id', $labelId)
            ->first();

        $assigned->delete();

        return ApiResponse::ok();
    }
}
