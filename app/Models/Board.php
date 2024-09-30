<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workspace()
    {
        return $this->belongTo(Workspace::class);
    }

    public function columns()
    {
        return $this->hasMany(Column::class);
    }
}