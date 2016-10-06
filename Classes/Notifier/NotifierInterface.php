<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\SingletonInterface;

interface NotifierInterface extends SingletonInterface
{

    /**
     * @param Event $event
     * @param array $subscriptionRecord
     * @param string $notificationContent
     * @return void
     */
    public function notify(Event $event, $subscriptionRecord, $notificationContent);

}
