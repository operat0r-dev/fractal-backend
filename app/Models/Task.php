<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property int $column_id
 * @property int $seq
 */
class Task extends Model
{
    protected $fillable = [
        'id',
        'title',
        'column_id',
        'seq'
    ];

    protected $hidden = [
        'created_at',  
        'updated_at',  
    ];

    public function column()
    {
        return $this->belongTo(Column::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }
}
