<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $task = Task::create([
            'title' => $request->get('title'),
            'column_id' => $request->get('column_id'),
            'seq' => $request->get('seq'),
        ]);

        return ApiResponse::created($task->toArray());
    }

    public function move(Request $request, int $id)
    {
        $task = Task::find($id);

        $task->update([
            'column_id' => $request->get('column_id'),
            'seq' => $request->get('seq'),
        ]);

        return ApiResponse::ok();
    }

    public function reorder(Request $request, int $id)
    {
        $task = Task::find($id);

        $task->update([
            'seq' => $request->get('seq'),
        ]);

        return ApiResponse::ok();
    }
}
