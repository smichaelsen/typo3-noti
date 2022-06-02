<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Smichaelsen\Noti\Event\EventRegistry;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NotificationSettingsController
{
    private EventRegistry $eventRegistry;
    private ModuleTemplate $moduleTemplate;

    public function __construct()
    {
        $this->eventRegistry = GeneralUtility::makeInstance(EventRegistry::class);
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
    }

    public function subscriptionsAction(): ResponseInterface
    {
        $events = $this->eventRegistry->getEvents();
        $content = '<h1>Manage your subscriptions</h1>';
        $this->moduleTemplate->setContent($content);
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }
}
