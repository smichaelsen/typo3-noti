<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Event\EventInterface;

class NotificationCenterNotifier extends AbstractNotifier
{
    public function notify(EventInterface $event, int $userId)
    {
    }
}
