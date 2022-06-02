<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smichaelsen\Noti\Event\EventRegistry;
use Smichaelsen\Noti\Notifier\NotifierRegistry;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

class NotificationSettingsController
{
    private Connection $connection;
    private EventRegistry $eventRegistry;
    private ModuleTemplate $moduleTemplate;
    private NotifierRegistry $notifierRegistry;

    public function __construct(ConnectionPool $connectionPool, EventRegistry $eventRegistry, NotifierRegistry $notifierRegistry)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_subscription');
        $this->eventRegistry = $eventRegistry;
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->notifierRegistry = $notifierRegistry;
    }

    public function subscriptionsAction(ServerRequestInterface $request): ResponseInterface
    {
        $postedData = GeneralUtility::_POST('tx_noti');
        if (is_array($postedData)) {
            $this->savePostedData($postedData);
        }

        $formAction = (string)GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoutePath($request->getAttribute('route')->getPath());
        $view = $this->createView();
        $view->assign('events', $this->eventRegistry->getEvents());
        $view->assign('existingSubscriptions', ['Smichaelsen\\Noti\\Event\\LogEntry\\emergency_Smichaelsen\\Noti\\Notifier\\EmailNotifier']);
        $view->assign('notifiers', $this->notifierRegistry->getNotifiers());
        $view->assign('formAction', $formAction);
        $this->moduleTemplate->setContent($view->render('Subscriptions'));
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    protected function createView(): ViewInterface
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths(['EXT:noti/Resources/Private/Templates']);
        $view->setPartialRootPaths(['EXT:noti/Resources/Private/Partials']);
        $view->setLayoutRootPaths(['EXT:noti/Resources/Private/Layouts']);
        $view->getRequest()->setControllerExtensionName('Noti');
        return $view;
    }

    protected function savePostedData(array $postedData): void
    {
        $selectedUser = $GLOBALS['BE_USER']->user['uid'];
        $this->connection->delete(
            'tx_noti_subscription',
            [
                'user' => $selectedUser,
            ]
        );
        foreach ($postedData as $subscriptionKey => $choice) {
            if ($choice !== 'on') {
                continue;
            }
            [$eventKey, $notifierKey] = explode('_', $subscriptionKey);
            $this->connection->insert(
                'tx_noti_subscription',
                [
                    'event_key' => $eventKey,
                    'notifier_key' => $notifierKey,
                    'user' => $selectedUser,
                ]
            );
        }
    }
}
