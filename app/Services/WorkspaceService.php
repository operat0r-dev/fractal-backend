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
            'name' => $name,
            'description' => $name,
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
        
        try {
            $workspace = Workspace::create([
                'name' => 'Fractal',
                'description' => 'Workspace dedicated to planning and executing the new product launch.',
            ]);

            $user->workspaces()->attach($workspace->id);

            $workspaceWithPivot = $user->workspaces()->withPivot('current')->find($workspace->id);
            $this->setCurrentWorkspace($workspace, $user);

            $board = $this->createBoard('Product launch', $workspace->id, 'hsl(30, 90%, 66%)');
            $column1 = $this->createColumn('Initial Research', $board->id, 16338, 'hsl(170, 90%, 66%)');
            $column2 = $this->createColumn('Development', $board->id, 16338 * 2, 'hsl(200, 90%, 66%)');
            $column3 = $this->createColumn('Marketing & Launch', $board->id, 16338 * 3, 'hsl(230, 90%, 66%)');

            $task1 = $this->createTask(
                'Market Research',
                $column1->id,
                16338
            );

            $task2 = $this->createTask(
                'Design Wireframes',
                $column2->id,
                16338
            );

            $task3 = $this->createTask(
                'Develop MVP',
                $column2->id,
                16338 * 2
            );

            $this->createTask(
                'Launch Campaign',
                $column3->id,
                16338
            );

            $label1 = $this->createLabel('High Priority', 'hsl(0, 90%, 66%)', $board->id);
            $label2 = $this->createLabel('Medium priority', 'hsl(30, 90%, 66%)', $board->id);
            $label3 = $this->createLabel('Low priority', 'hsl(140, 90%, 66%)', $board->id);

            $this->assignLabel($task3->id, $label3->id);
            $this->assignLabel($task2->id, $label2->id);
            $this->assignLabel($task1->id, $label1->id);

            return $workspaceWithPivot;
        } catch (\Exception $e) {
            return $e;
        }
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
    }

    private function createBoard(string $name, int $workspace_id, string $color)
    {
        $board = Board::create([
            'name' => $name,
            'workspace_id' => $workspace_id,
            'color' => $color
        ]);

        return $board;
    }

    private function createColumn(string $name, int $board_id, int $seq, string $color)
    {
        $column = Column::create([
            'name' => $name,
            'board_id' => $board_id,
            'seq' => $seq,
            'color' => $color,
        ]);

        return $column;
    }

    private function createTask(string $title, int $column_id, int $seq)
    {
        $task = Task::create([
            'title' => $title,
            'column_id' => $column_id,
            'seq' => $seq,
            'user_id' => null,
        ]);

        return $task;
    }

    private function createLabel(string $name, string $color, int $board_id)
    {
        $label = Label::create([
            'name' => $name,
            'color' => $color,
            'board_id' => $board_id,
        ]);

        return $label;
    }

    private function assignLabel(int $label_id, int $task_id)
    {
        LabelTask::create([
            'label_id' => $label_id,
            'task_id' => $task_id,
        ]);
    }
}
