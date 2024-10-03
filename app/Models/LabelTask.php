<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $label_id
 * @property int $task_id
 */
class LabelTask extends Model
{
    protected $fillable = [
        'id',
        'label_id',
        'task_id',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}
