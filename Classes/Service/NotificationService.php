<?php

namespace Smichaelsen\Noti\Service;

use Smichaelsen\Noti\Event\EventInterface;
use Smichaelsen\Noti\Notifier\EmailNotifier;
use Smichaelsen\Noti\Notifier\NotifierInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationService implements SingletonInterface
{
    private Connection $connection;
    private ExtensionConfiguration $extensionConfiguration;
    private NotifierRegistry $notifierRegistry;

    public function __construct(
        ConnectionPool $connectionPool,
        ExtensionConfiguration $extensionConfiguration,
        NotifierRegistry $notifierRegistry
    )
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_subscription');
        $this->extensionConfiguration = $extensionConfiguration;
        $this->notifierRegistry = $notifierRegistry;
    }

    public function __invoke(EventInterface $event): EventInterface
    {
        $eventKey = get_class($event) . '\\' . ($event->getVariant() ?? '_default');
        $subscriptions = $this->loadSubscriptionsForEventKey($eventKey);
        foreach ($subscriptions as $subscription) {
            if (
                $subscription['notifier_key'] === get_class(new EmailNotifier()) &&
                $this->extensionConfiguration->get('noti', 'configuration/disableEmailNotifications')
            ) {
                continue;
            }
            if (!in_array($subscription['notifier_key'], $this->notifierRegistry->getAvailableNotifierKeys())) {
                continue;
            }
            /** @var NotifierInterface $notifier */
            $notifier = GeneralUtility::makeInstance($subscription['notifier_key']);
            $notifier->notify($event, (int)$subscription['user']);
        }
        return $event;
    }

    private function loadSubscriptionsForEventKey(string $eventKey): array
    {
        return $this->connection->select(
            ['uid', 'user', 'notifier_key'],
            'tx_noti_subscription',
            ['event_key' => $eventKey]
        )->fetchAllAssociative();
    }
}
