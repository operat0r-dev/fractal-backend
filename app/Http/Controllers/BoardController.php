<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Board;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index(int $id): ApiResponse
    {
        try {
            $board = Board::with([
                'columns',
                'columns.tasks' => function ($query) {
                    $query->orderBy('seq');
                },
                'columns.tasks.labels' => function ($query) {
                    $query->orderBy('name');
                },
                'columns.tasks.user',
            ])->findOrFail($id);

            $workspaceId = $board->workspace_id;
            $user = Auth::user();

            if (!$user->workspaces->where('workspace_id', $workspaceId)->exists()) {
                return ApiResponse::forbidden();
            }

            return ApiResponse::ok($board->toArray());
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound();
        }
    }

    public function store(BoardRequest $request): ApiResponse
    {
        $board = Board::create($request->only(['name', 'workspace_id', 'color']));

        return ApiResponse::ok($board->toArray());
    }
}
