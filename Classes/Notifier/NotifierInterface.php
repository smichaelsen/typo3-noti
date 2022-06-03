<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Event\EventInterface;
use TYPO3\CMS\Core\SingletonInterface;

interface NotifierInterface extends SingletonInterface
{
    public function notify(EventInterface $event, int $userId);
}
