<?php
namespace Smichaelsen\Noti\Service;

use Smichaelsen\Noti\Domain\Model\Event;
use Smichaelsen\Noti\Event\EventInterface;
use Smichaelsen\Noti\Notifier\NotifierInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationService implements SingletonInterface
{
    private Connection $connection;
    private LanguageService $languageService;

    public function __construct(ConnectionPool $connectionPool, LanguageService $languageService)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_subscription');
        $this->languageService = $languageService;
    }

    public function __invoke(EventInterface $event): EventInterface
    {
        $subscriptions = $this->loadSubscriptions();
        foreach ($subscriptions as $subscription) {
            $notifier = $this->getNotifierForSubscription($subscription);
            $variables['eventTitle'] = $this->languageService->sL($notification->getTitle());
            $variables['notifier'] = $notifier;
            $variables['notifierClassName'] = get_class($notifier);
            $notifier->notify($event, $subscription, $variables);
        }
        return $notification;
    }

    public function triggerEvent(string $identifier, array $variables = []): void
    {
        $this->notify($this->eventRegistry->getEvent($identifier), $variables);
    }

    /**
     * @param Event $event
     * @param array $variables
     */
    public function notify(Event $event, array $variables): void
    {
        $subscriptions = $this->loadSubscriptions($event);
        foreach ($subscriptions as $subscription) {
            $notifier = $this->getNotifierForSubscription($subscription);
            $variables['eventTitle'] = $this->languageService->sL($event->getTitle());
            $variables['notifier'] = $notifier;
            $variables['notifierClassName'] = get_class($notifier);
            $notifier->notify($event, $subscription, $variables);
        }
    }

    protected function loadSubscriptions(Event $event): array
    {
        return $this->connection->select(
            ['*'],
            'tx_noti_subscription',
            ['event', $event->getIdentifier()]
        )->fetchAllAssociative();
    }

    protected function getNotifierForSubscription(array $subscription): NotifierInterface
    {
        $notifier = GeneralUtility::makeInstance($subscription['type']);
        if (!$notifier instanceof NotifierInterface) {
            throw new \Exception($subscription['type'] . ' doesn\'t implement the NotifierInterface', 1475737136);
        }
        return $notifier;
    }
}
