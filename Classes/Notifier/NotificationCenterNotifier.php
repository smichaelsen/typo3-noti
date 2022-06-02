<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;

class NotificationCenterNotifier extends AbstractNotifier
{
    public function notify(Event $event, $subscriptionRecord, $variables)
    {
    }
}
