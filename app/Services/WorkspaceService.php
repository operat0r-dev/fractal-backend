<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Board;
use App\Models\Column;
use App\Models\Label;
use App\Models\LabelTask;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

class WorkspaceService
{
    public function createWorkspace(string $name, User $user)
    {
        $workspace = Workspace::create([
            'name' => $name
        ]);

        $user->workspaces()->attach($workspace->id);

        $workspaceWithPivot = $user->workspaces()->withPivot('current')->find($workspace->id);

        return $workspaceWithPivot;
    }

    public function createDefaultWorkspace(string $name, User $user)
    {
        $workspace = Workspace::create([
            'name' => $name
        ]);

        $user->workspaces()->attach($workspace->id);

        $workspaceWithPivot = $user->workspaces()->withPivot('current')->find($workspace->id);
        $this->setCurrentWorkspace($workspace, $user);
        $board = $this->createDefaultBoard($name, $workspace->id);
        $column = $this->createDefaultColumn($name, $board->id, 16338, 'hsl(60, 90%, 66%)');
        $task = $this->createDefaultTask($name, $column->id, 16338);
        $this->createDefaultLabels($name, 'hsl(60, 90%, 66%)', $board->id, $task->id);

        return $workspaceWithPivot;
    }

    private function setCurrentWorkspace(Workspace $workspace, User $user)
    {
        $user->workspaces()->updateExistingPivot($workspace->id, ['current' => true]);
    }
    
    private function createDefaultBoard(string $name, int $workspace_id)
    {
        $board = Board::create([
            'name'=> $name,
            'workspace_id' => $workspace_id
        ]);
        $board->save();

        return $board;
    }

    private function createDefaultColumn(string $name, int $board_id, int $seq, string $color)
    {
        $column = Column::create([
            'name' => $name,
            'board_id' => $board_id,
            'seq' => $seq,
            'color'=> $color,
        ]);
        $column->save();

        return $column;
    }

    private function createDefaultTask(string $title, int $column_id, int $seq)
    {
        $task = Task::create([
            'title' => $title,
            'column_id' => $column_id,
            'seq' => $seq
        ]);
        $task->save();

        return $task;
    }
    
    private function createDefaultLabels(string $name, string $color, int $board_id, int $task_id)
    {
        $label = Label::create([
            'name'=> $name,
            'color'=> $color,
            'board_id'=> $board_id
        ]);

        $label->save();

        $labaleTask = LabelTask::create([
            'label_id' => $label->id,
            'task_id' => $task_id,
        ]);

        $labaleTask->save();
    }
}
