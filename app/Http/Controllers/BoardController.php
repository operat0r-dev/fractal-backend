<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(int $id): ApiResponse
    {
        $board = Board::with(['columns', 'columns.tasks' => function ($query) {
            $query->orderBy('seq', 'asc');
        }])->find($id);

        return ApiResponse::ok($board->toArray());
    }

    public function store(Request $request): ApiResponse
    {
        $board = Board::create([
            'name' => $request->get('name'),
            'workspace_id' => $request->get('workspaceId'),
        ]);

        return ApiResponse::ok($board->toArray());
    }
}
