<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    ];

    public function workspace(): mixed
    {
        return $this->belongTo(Workspace::class);
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
