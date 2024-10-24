<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Models\EventType;

class EventService
{
    public function createEvent(int $taskId, int $userId, string $title): Event
    {
        $eventTypeId = $this->getEventTypeByTitle($title);

        return Event::create([
            'event_type_id' => $eventTypeId,
            'task_id' => $taskId,
            'user_id' => $userId,
        ]);
    }

    private function getEventTypeByTitle(string $title)
    {
        return EventType::where('title', $title)->firstOrFail()->id;
    }
}
