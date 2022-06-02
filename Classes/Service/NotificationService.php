<?php
namespace Smichaelsen\Noti\Service;

use Smichaelsen\Noti\Event\EventInterface;
use TYPO3\CMS\Core\SingletonInterface;

class NotificationService implements SingletonInterface
{
    public function __invoke(EventInterface $event): EventInterface
    {
        return $event;
    }
}
