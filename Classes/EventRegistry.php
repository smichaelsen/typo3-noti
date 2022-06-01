<?php

namespace Smichaelsen\Noti;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\SingletonInterface;

class EventRegistry implements SingletonInterface
{
    protected array $eventRegistry = [];

    public function registerEvent(Event $event): void
    {
        $this->eventRegistry[$event->getIdentifier()] = $event;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->eventRegistry;
    }

    public function getEvent(string $identifier): Event
    {
        if (!self::hasEvent($identifier)) {
            throw new \Exception('Event ' . $identifier() . ' is not registered', 1475678573);
        }
        return $this->eventRegistry[$identifier];
    }

    public function hasEvent(string $identifier): bool
    {
        return isset($this->eventRegistry[$identifier]);
    }
}
