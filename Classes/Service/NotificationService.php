<?php
namespace Smichaelsen\Noti\Service;

use Smichaelsen\Noti\Domain\Model\Event;
use Smichaelsen\Noti\Notifier\NotifierInterface;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Lang\LanguageService;

class NotificationService implements SingletonInterface
{

    /**
     * @param Event $event
     * @param array $variables
     */
    public function notify(Event $event, $variables)
    {
        $subscriptions = $this->loadSubscriptions($event);
        foreach ($subscriptions as $subscription) {
            $notifier = $this->getNotifierForSubscription($subscription);
            $variables['eventTitle'] = $this->getLanguageService()->sL($event->getTitle());
            $variables['notifier'] = $notifier;
            $variables['notifierClassName'] = get_class($notifier);
            $notificationContent = $this->renderNotificationContent($subscription, $variables);
            $notifier->notify($event, $subscription, $notificationContent);
        }
    }

    /**
     * @param Event $event
     * @return array
     */
    protected function loadSubscriptions(Event $event)
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows(
            '*',
            'tx_noti_subscription',
            'event = ' . $this->getDatabaseConnection()->fullQuoteStr($event->getIdentifier(), 'tx_noti_subscription') . ' AND deleted = 0 AND hidden = 0'
        );
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param string $subscription
     * @return NotifierInterface
     * @throws \Exception
     */
    protected function getNotifierForSubscription($subscription)
    {
        $notifier = GeneralUtility::makeInstance($subscription['type']);
        if (!$notifier instanceof NotifierInterface) {
            throw new \Exception($subscription['type'] . ' doesn\'t implement the NotifierInterface', 1475737136);
        }
        return $notifier;
    }

    /**
     * @param array $variables
     * @return string
     */
    protected function renderNotificationContent($subscription, $variables)
    {
        $view = GeneralUtility::makeInstance(ObjectManager::class)->get(StandaloneView::class);
        $view->setTemplateSource($subscription['text']);
        $view->assignMultiple($variables);
        return $view->render();
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
