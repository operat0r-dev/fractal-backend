<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    const TYPE_USER_ASSIGNED = 'User Unassigned';

    const TYPE_USER_UNASSIGNED = 'User Assigned';

    const TYPE_LABEL_ASSIGNED = 'Label Unassigned';

    const TYPE_LABEL_UNASSIGNED = 'Label Assigned';
    const MOVED = 'Moved';

    const TYPE_DESCRIPTION_CHANGED = 'Description Changed';

    const TYPE_TITLE_UNASSIGNED = 'Title Changed';

    protected $fillable = [
        'id',
        'title',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
