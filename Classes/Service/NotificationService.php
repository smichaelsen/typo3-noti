<?php
namespace Smichaelsen\Noti\Service;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\SingletonInterface;

class NotificationService implements SingletonInterface
{

    /**
     * @param Event $event
     * @param array $variables
     */
    public function notify(Event $event, $variables)
    {

    }

}
