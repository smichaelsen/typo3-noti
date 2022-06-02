<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Smichaelsen\Noti\Event\EventRegistry;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

class NotificationSettingsController
{
    private EventRegistry $eventRegistry;
    private ModuleTemplate $moduleTemplate;

    public function __construct(EventRegistry $eventRegistry)
    {
        $this->eventRegistry = $eventRegistry;
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
    }

    public function subscriptionsAction(): ResponseInterface
    {
        $view = $this->createView();
        $view->assign('events', $this->eventRegistry->getEvents());
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
}
