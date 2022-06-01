<?php

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\SingletonInterface;

interface NotifierInterface extends SingletonInterface
{
    public function notify(Event $event, array $subscriptionRecord, array $variables): void;
}
