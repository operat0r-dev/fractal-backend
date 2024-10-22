<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Workspace extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}
