<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Backend\Toolbar;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class NotificationCenterToolbarItem implements ToolbarItemInterface
{
    private Connection $connection;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_notification');
    }

    public function checkAccess(): bool
    {
        return true;
    }

    public function getItem(): string
    {
        $view = $this->getFluidTemplateObject('NotificationCenterToolbarItem.html');
        $unreadNotifications = $this->loadUnreadNotifications();
        $count = count($unreadNotifications);
        if ($count > 99) {
            $count = '99+';
        }
        $view->assign('unreadNotificationsCount', $count);
        return $view->render();
    }

    public function hasDropDown(): bool
    {
        return true;
    }

    public function getDropDown(): string
    {
        $view = $this->getFluidTemplateObject('NotificationCenterToolbarDropDown.html');
        $unreadNotifications = $this->loadUnreadNotifications();
        $latestUnreadNotifications = array_slice($unreadNotifications, 0, 3);
        $view->assign('unreadNotificationsCount', count($unreadNotifications));
        $view->assign('latestUnreadNotifications', $latestUnreadNotifications);
        $view->assign('hasSettingsAccess', $this->hasSettingsAccess());
        return $view->render();
    }

    private function loadUnreadNotifications(): array
    {
        return $this->connection->select(
            ['uid', 'title', 'icon_identifier'],
            'tx_noti_notification',
            [
                'user' => $this->getBackendUser()->user['uid'],
                'read' => '0',
            ],
            [],
            [],
            100,
        )->fetchAllAssociative();
    }

    public function getAdditionalAttributes(): array
    {
        return [];
    }

    public function getIndex(): int
    {
        return 63;
    }

    protected function getFluidTemplateObject(string $filename): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setLayoutRootPaths(['EXT:noti/Resources/Private/Layouts']);
        $view->setPartialRootPaths(['EXT:backend/Resources/Private/Partials/ToolbarItems', 'EXT:noti/Resources/Private/Partials']);

        $view->setTemplatePathAndFilename('EXT:noti/Resources/Private/Templates/' . $filename);

        return $view;
    }

    private function hasSettingsAccess(): bool
    {
        $backendUser = $this->getBackendUser();
        if ($backendUser->isAdmin()) {
            return true;
        }
        if (in_array('user_notification_settings', GeneralUtility::trimExplode(',', $backendUser->groupData['modules']))) {
            return true;
        }
        return false;
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
