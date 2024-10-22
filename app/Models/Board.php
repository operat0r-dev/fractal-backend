<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $workspace_id
 */
class Board extends Model
{
    protected $fillable = [
        'id',
        'name',
        'workspace_id',
        'color'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}
