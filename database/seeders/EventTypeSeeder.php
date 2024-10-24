<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTypes = [
            ['title' => 'Label Assigned'],
            ['title' => 'Label Unassigned'],
            ['title' => 'User Assigned'],
            ['title' => 'User Unassigned'],
            ['title' => 'Moved'],
            ['title' => 'Description Changed'],
            ['title' => 'Title Changed'],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
