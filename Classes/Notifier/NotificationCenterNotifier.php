<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Event\EventInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class NotificationCenterNotifier extends AbstractNotifier
{
    private Connection $connection;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_notification');
    }

    public function notify(EventInterface $event, int $userId)
    {
        $this->connection->insert(
            'tx_noti_notification',
            [
                'crdate' => $GLOBALS['EXEC_TIME'],
                'tstamp' => $GLOBALS['EXEC_TIME'],
                'user' => $userId,
                'title' => $event->getTitle(),
                'message' => $event->getMessage(),
                'is_html_message' => (int)$event->isMessageHtml(),
                'icon_identifier' => $event->getIconIdentifier(),
            ],
        );
    }
}
