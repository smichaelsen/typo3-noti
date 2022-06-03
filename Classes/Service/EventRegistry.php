<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Service;

class EventRegistry
{
    private array $events;

    public function addEvent(string $eventKey, string $eventLabel): void
    {
        $this->events[$eventKey] = $eventLabel;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
