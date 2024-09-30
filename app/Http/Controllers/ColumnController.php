<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function store(Request $request): ApiResponse
    {
        $column = Column::create([
            'name' => $request->get('name'),
            'board_id' => $request->get('board_id'),
            'seq' => $request->get('seq'),
        ]);

        $column->load('tasks');

        return ApiResponse::created($column->toArray());
    }

    public function update(Request $request, int $id): ApiResponse
    {
        $column = Column::find($id);

        $column->update(
            $request->only(['name', 'seq', 'color'])
        );

        $column->load('tasks');

        return ApiResponse::ok($column->toArray());
    }
}
