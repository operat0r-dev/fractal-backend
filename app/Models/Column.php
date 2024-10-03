<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $board_id
 * @property int $seq
 */
class Column extends Model
{
    protected $fillable = [
        'id',
        'name',
        'board_id',
        'seq',
        'color'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
