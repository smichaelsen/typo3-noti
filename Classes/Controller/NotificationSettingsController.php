<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smichaelsen\Noti\Service\EventRegistry;
use Smichaelsen\Noti\Service\NotifierRegistry;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationSettingsController extends AbstractBackendController
{
    private EventRegistry $eventRegistry;
    private NotifierRegistry $notifierRegistry;

    public function injectEventRegistry(EventRegistry $eventRegistry): void
    {
        $this->eventRegistry = $eventRegistry;
    }

    public function injectNotifierRegistry(NotifierRegistry $notifierRegistry): void
    {
        $this->notifierRegistry = $notifierRegistry;
    }

    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        $this->moduleTemplate->setTitle('Notification Settings');
        $this->generateMenu($request);
        $content = $this->subscriptionsAction($request);
        $this->moduleTemplate->getView()->assign('content', $content);

        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    private function subscriptionsAction(ServerRequestInterface $request): string
    {
        $selectedUser = $this->getBackendUser()->user['uid'];
        $parsedBody = $request->getParsedBody();
        if ($this->getBackendUser()->isAdmin()) {
            $queryParams = $request->getQueryParams();
            $querySelectedUser = isset($queryParams['selectedUser']) ? (int)$queryParams['selectedUser'] : null;
            $bodySelectedUser = isset($parsedBody['selectedUser']) ? (int)$parsedBody['selectedUser'] : null;
            if ($querySelectedUser !== null) {
                $selectedUser = $querySelectedUser;
            } elseif ($bodySelectedUser !== null) {
                $selectedUser = $bodySelectedUser;
            }
        }
        $postedData = $parsedBody['tx_noti'] ?? [];
        if (!empty($postedData)) {
            $this->savePostedData($postedData, $selectedUser);
        }

        $formAction = (string)GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoutePath($request->getAttribute('route')->getPath());

        $view = $this->createView($request);
        $view->assign('events', $this->eventRegistry->getEvents());
        $view->assign('existingSubscriptions', $this->loadExistingSubscriptions($selectedUser));
        $view->assign('notifiers', $this->notifierRegistry->getNotifiers());
        $view->assign('formAction', $formAction);
        $view->assign('selectedUser', $selectedUser);
        if ($this->getBackendUser()->isAdmin()) {
            $this->addBackendUserSelector($selectedUser);
        }
        return $view->render('Subscriptions');
    }

    protected function savePostedData(array $postedData, int $selectedUser): void
    {
        $existingSubscriptions = $this->loadExistingSubscriptions($selectedUser);
        foreach ($postedData as $subscriptionKey => $choice) {
            if ($choice !== 'on' && in_array($subscriptionKey, $existingSubscriptions)) {
                $this->getConnection()->delete('tx_noti_subscription', ['uid' => array_search($subscriptionKey, $existingSubscriptions)]);
            } elseif ($choice === 'on' && !in_array($subscriptionKey, $existingSubscriptions)) {
                [$eventKey, $notifierKey] = explode('|', $subscriptionKey);
                $this->getConnection()->insert(
                    'tx_noti_subscription',
                    [
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
        $result = $this->getConnection()->select(
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

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    protected function loadBackendUsers(): array
    {
        $backendUserRecords = $this->getConnection()->select(
            ['uid', 'realName', 'username'],
            'be_users',
            ['deleted' => 0],
        )->fetchAllAssociative();
        usort(
            $backendUserRecords,
            fn(array $userA, array $userB) => ($userA['realName'] ?: $userA['username']) <=> ($userB['realName'] ?: $userB['username'])
        );
        return $backendUserRecords;
    }

    private function addBackendUserSelector(int $selectedUser): void
    {
        $menu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('NotificationUserMenu');
        foreach ($this->loadBackendUsers() as $backendUser) {
            $menuItem = $menu
                ->makeMenuItem()
                ->setHref(
                    $this->uriBuilder->buildUriFromRoute('user_notification_settings', ['selectedUser' => $backendUser['uid']])
                )
                ->setTitle($backendUser['realName'] ?: $backendUser['username']);
            if ($selectedUser === (int)$backendUser['uid']) {
                $menuItem->setActive(true);
            }
            $menu->addMenuItem($menuItem);
        }
        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }
}
