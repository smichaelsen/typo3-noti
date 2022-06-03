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
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

class NotificationSettingsController extends AbstractBackendController
{
    private EventRegistry $eventRegistry;
    private NotifierRegistry $notifierRegistry;

    /** @noinspection PhpUnused */
    public function injectEventRegistry(EventRegistry $eventRegistry)
    {
        $this->eventRegistry = $eventRegistry;
    }

    /** @noinspection PhpUnused */
    public function injectNotifierRegistry(NotifierRegistry $notifierRegistry)
    {
        $this->notifierRegistry = $notifierRegistry;
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->generateMenu($request);
        $content = $this->subscriptionsAction($request);
        $this->moduleTemplate->setContent($content);
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    private function subscriptionsAction(ServerRequestInterface $request): string
    {
        if ((isset($request->getQueryParams()['selectedUser']) || isset($request->getParsedBody()['selectedUser'])) && $this->getBackendUser()->isAdmin()) {
            $selectedUser = (int)($request->getQueryParams()['selectedUser'] ?: $request->getParsedBody()['selectedUser']);
        } else {
            $selectedUser = $this->getBackendUser()->user['uid'];
        }
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
        $view->assign('selectedUser', $selectedUser);
        if ($this->getBackendUser()->isAdmin()) {
            $this->addBackendUserSelector($selectedUser);
        }
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

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    protected function loadBackendUsers(): array
    {
        $backendUserRecords = $this->connection->select(
            ['uid', 'realName', 'username'],
            'be_users',
            ['deleted' => 0],
        )->fetchAllAssociative();
        usort(
            $backendUserRecords,
            fn (array $userA, array $userB) => ($userA['realName'] ?: $userA['username']) <=> ($userB['realName'] ?: $userB['username'])
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
