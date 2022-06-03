<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\UserFunc;

use Smichaelsen\Noti\Service\EventRegistry;
use Smichaelsen\Noti\Service\NotifierRegistry;

class TcaUserFunc
{
    private EventRegistry $eventRegistry;
    private NotifierRegistry $notifierRegistry;

    public function __construct(EventRegistry $eventRegistry, NotifierRegistry $notifierRegistry)
    {
        $this->eventRegistry = $eventRegistry;
        $this->notifierRegistry = $notifierRegistry;
    }

    public function eventItems(&$params): void
    {
        foreach ($this->eventRegistry->getEvents() as $key => $label) {
            $params['items'][] = [$label, $key];
        }
    }

    public function notifierItems(&$params): void
    {
        foreach ($this->notifierRegistry->getNotifiers() as $key => $label) {
            $params['items'][] = [$label, $key];
        }
    }
}
