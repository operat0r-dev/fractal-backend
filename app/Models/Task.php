<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property int $column_id
 * @property int $seq
 * @property string $description
 */
class Task extends Model
{
    protected $fillable = [
        'id',
        'title',
        'column_id',
        'seq',
        'user_id',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class)->withPivot('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
