<?php
namespace Smichaelsen\Noti\UserFunc;

use Smichaelsen\Noti\EventRegistry;

class SubscriptionTcaUserFunctions
{
    private EventRegistry $eventRegistry;

    public function __construct(EventRegistry $eventRegistry)
    {
        $this->eventRegistry = $eventRegistry;
    }

    public function availableEventsItemsProcFunc(array &$parameters)
    {
        $eventsByExtensionKey = [];
        foreach ($this->eventRegistry->getEvents() as $event) {
            if (!is_array($eventsByExtensionKey[$event->getExtensionKey()])) {
                $eventsByExtensionKey[$event->getExtensionKey()] = [];
            }
            $eventsByExtensionKey[$event->getExtensionKey()][] = $event;
        }
        foreach ($eventsByExtensionKey as $extensionKey => $events) {
            $parameters['items'][] = [$extensionKey, '--div--'];
            foreach ($events as $event) {
                $parameters['items'][] = [$event->getTitle(), $event->getIdentifier()];
            }
        }
    }
}
