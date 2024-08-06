<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

abstract class AbstractBackendController implements ControllerInterface
{
    protected Connection $connection;
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $uriBuilder;
    protected ModuleTemplate $moduleTemplate;

    public function __construct(
        ConnectionPool $connectionPool,
        UriBuilder $uriBuilder,
        ModuleTemplateFactory $moduleTemplateFactory
    ) {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_notification');
        $this->uriBuilder = $uriBuilder;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }


    protected function initialize(ServerRequestInterface $request): void
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($request);
    }

    protected function createView(ServerRequestInterface $request): ViewInterface
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths(['EXT:noti/Resources/Private/Templates']);
        $view->setPartialRootPaths(['EXT:noti/Resources/Private/Partials']);
        $view->setLayoutRootPaths(['EXT:noti/Resources/Private/Layouts/']);
        $view->setRequest($request);
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
