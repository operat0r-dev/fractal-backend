<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request): ApiResponse
    {
        $task = Task::create([
            'title' => $request->get('title'),
            'column_id' => $request->get('column_id'),
            'seq' => $request->get('seq'),
        ]);

        $task->load('labels');

        return ApiResponse::created($task->toArray());
    }

    public function update(Request $request, int $id): ApiResponse
    {
        $task = Task::find($id);

        $task->update($request->only(['column_id', 'seq']));

        return ApiResponse::ok();
    }
}
