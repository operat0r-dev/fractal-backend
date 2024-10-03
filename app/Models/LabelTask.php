<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $label_id
 * @property int $task_id
 */
class LabelTask extends Model
{
    protected $table = 'label_task';

    protected $fillable = [
        'id',
        'label_id',
        'task_id',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
