<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

abstract class AbstractBackendController
{
    protected Connection $connection;
    protected ModuleTemplate $moduleTemplate;
    protected UriBuilder $uriBuilder;

    /** @noinspection PhpUnused */
    public function injectConnection(ConnectionPool $connectionPool)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_notification');
    }

    /** @noinspection PhpUnused */
    public function injectUriBuilder(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function __construct()
    {
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
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

    protected function generateMenu(ServerRequestInterface $request): void
    {
        $currentRoute = $request->getAttribute('route');
        $currentRouteName = $currentRoute->getOption('_identifier');
        $menu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('NotificationsMenu');
        if ($this->getBackendUser()->isAdmin() || in_array('user_notification_settings', GeneralUtility::trimExplode(',', $this->getBackendUser()->groupData['modules']))) {
            $subscriptionsMenuItem = $menu
                ->makeMenuItem()
                ->setHref(
                    $this->uriBuilder->buildUriFromRoute('user_notification_settings')
                )
                ->setTitle('Subscriptions');
            if ($currentRouteName === 'user_notification_settings') {
                $subscriptionsMenuItem->setActive(true);
            }
            $menu->addMenuItem($subscriptionsMenuItem);
        }
        $notificationsMenuItem = $menu
            ->makeMenuItem()
            ->setHref(
                $this->uriBuilder->buildUriFromRoute('user_notifications')
            )
            ->setTitle('Notifications');
        if ($currentRouteName === 'user_notifications') {
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
