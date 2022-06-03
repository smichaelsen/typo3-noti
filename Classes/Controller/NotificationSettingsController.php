<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smichaelsen\Noti\Service\EventRegistry;
use Smichaelsen\Noti\Service\NotifierRegistry;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
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
    private UriBuilder $uriBuilder;

    public function __construct(ConnectionPool $connectionPool, EventRegistry $eventRegistry, NotifierRegistry $notifierRegistry, UriBuilder $uriBuilder)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_subscription');
        $this->eventRegistry = $eventRegistry;
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->notifierRegistry = $notifierRegistry;
        $this->uriBuilder = $uriBuilder;
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getQueryParams()['action'] ?? $request->getParsedBody()['action'] ?? 'subscriptions';

        $this->generateMenu($action);

        if ($action === 'subscriptions') {
            $content = $this->subscriptionsAction($request);
        } elseif ($action === 'notifications') {
            $content = $this->notificationsAction($request);
        } else {
            throw new \InvalidArgumentException('Requested action ' . $action . ' is unavailable', 1654249507);
        }


        $this->moduleTemplate->setContent($content);
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    private function notificationsAction(ServerRequestInterface $request): string
    {
        if (isset($request->getQueryParams()['markAsRead'])) {
            $this->connection->update(
                'tx_noti_notification',
                ['read' => '1'],
                ['user' => $this->getBackendUser()->user['uid']],
            );
        }

        $view = $this->createView();
        $notifications = $this->connection->select(
            ['uid', 'title', 'icon_identifier', 'crdate', 'message', 'read'],
            'tx_noti_notification',
            ['user' => $this->getBackendUser()->user['uid']],
            [],
            ['crdate' => 'DESC'],
            100,
        )->fetchAllAssociative();
        $view->assign('notifications', $notifications);
        $view->assign('markAllReadLink', $this->uriBuilder->buildUriFromRoute('user_notifications', ['action' => 'notifications', 'markAsRead' => 'all']));
        return $view->render('Notifications');
    }

    private function subscriptionsAction(ServerRequestInterface $request): string
    {
        $selectedUser = $GLOBALS['BE_USER']->user['uid'];
        $postedData = GeneralUtility::_POST('tx_noti');
        if (is_array($postedData)) {
            $this->savePostedData($postedData, $selectedUser);
        }

        $formAction = (string)GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoutePath($request->getAttribute('route')->getPath());
        $view = $this->createView();
        $view->assign('events', $this->eventRegistry->getEvents());
        $view->assign('existingSubscriptions', $this->loadExistingSubscriptions($selectedUser));
        $view->assign('notifiers', $this->notifierRegistry->getNotifiers());
        $view->assign('formAction', $formAction);
        return $view->render('Subscriptions');
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

    protected function savePostedData(array $postedData, int $selectedUser): void
    {
        $existingSubscriptions = $this->loadExistingSubscriptions($selectedUser);
        foreach ($postedData as $subscriptionKey => $choice) {
            if ($choice !== 'on' && in_array($subscriptionKey, $existingSubscriptions)) {
                $this->connection->delete('tx_noti_subscription', ['uid' => array_search($subscriptionKey, $existingSubscriptions)]);
            } elseif ($choice === 'on' && !in_array($subscriptionKey, $existingSubscriptions)) {
                [$eventKey, $notifierKey] = explode('|', $subscriptionKey);
                $this->connection->insert(
                    'tx_noti_subscription',
                    [
                        'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
                        'event_key' => $eventKey,
                        'notifier_key' => $notifierKey,
                        'user' => $selectedUser,
                        'tstamp' => $GLOBALS['EXEC_TIME'],
                        'crdate' => $GLOBALS['EXEC_TIME'],
                    ],
                );
            }
        }
    }

    protected function loadExistingSubscriptions(int $selectedUser): array
    {
        $result = $this->connection->select(
            ['uid', 'event_key', 'notifier_key'],
            'tx_noti_subscription',
            ['user' => $selectedUser]
        )->fetchAllAssociative();
        $existingSubscriptions = [];
        foreach ($result as $subscriptionRecord) {
            $subscriptionKey = $subscriptionRecord['event_key'] . '|' . $subscriptionRecord['notifier_key'];
            $existingSubscriptions[$subscriptionRecord['uid']] = $subscriptionKey;
        }
        return $existingSubscriptions;
    }

    private function generateMenu(string $currentAction): void
    {
        $menu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('WebFuncJumpMenu');
        $subscriptionsMenuItem = $menu
            ->makeMenuItem()
            ->setHref(
                $this->uriBuilder->buildUriFromRoute('user_notifications', ['action' => 'subscriptions'])
            )
            ->setTitle('Subscriptions');
        if ($currentAction === 'subscriptions') {
            $subscriptionsMenuItem->setActive(true);
        }
        $menu->addMenuItem($subscriptionsMenuItem);
        $notificationsMenuItem = $menu
            ->makeMenuItem()
            ->setHref(
                $this->uriBuilder->buildUriFromRoute('user_notifications', ['action' => 'notifications'])
            )
            ->setTitle('Notifications');
        if ($currentAction === 'notifications') {
            $notificationsMenuItem->setActive(true);
        }
        $menu->addMenuItem($notificationsMenuItem);
        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
