<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Service;

class EventRegistry
{
    private array $events;

    /**
     * @param class-string<\Smichaelsen\Noti\Event\EventInterface> $className
     */
    public function addEvent(string $className): void
    {
        if (!is_subclass_of($className, \Smichaelsen\Noti\Event\EventInterface::class)) {
            throw new \InvalidArgumentException('Class ' . $className . ' must implement \Smichaelsen\Noti\Event\EventInterface', 1722953200);
        }

        foreach ($className::getAllPossibleVariants() as $variantName => $eventLabel) {
            $eventKey = $className . '\\' . $variantName;
            $this->events[$eventKey] = $eventLabel;
        }
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
