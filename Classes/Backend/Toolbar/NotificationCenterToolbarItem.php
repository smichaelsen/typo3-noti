<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Backend\Toolbar;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class NotificationCenterToolbarItem implements ToolbarItemInterface
{
    private UriBuilder $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function checkAccess(): bool
    {
        // TODO: Implement checkAccess() method.
        return true;
    }

    public function getItem(): string
    {
        $view = $this->getFluidTemplateObject('NotificationCenterToolbarItem.html');
        $unreadNotifications = $this->loadUnreadNotifications();
        $view->assign('unreadNotificationsCount', count($unreadNotifications));
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
        $view->assign('notificationSettingsUrl', '');//$this->uriBuilder->buildUriFromRoute('noti_settings'));
        return $view->render();
    }

    private function loadUnreadNotifications(): array
    {
        return [];
    }

    public function getAdditionalAttributes()
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
        $view->setTemplateRootPaths(['EXT:noti/Resources/Private/Templates']);

        $view->setTemplate($filename);

        $view->getRequest()->setControllerExtensionName('Noti');
        return $view;
    }
}
