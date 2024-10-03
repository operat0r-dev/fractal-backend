<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $board_id
 */
class Label extends Model
{
    protected $fillable = [
        'id',
        'name',
        'color',
        'board_id'
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
