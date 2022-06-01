<?php

namespace Smichaelsen\Noti\Notifier;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

abstract class AbstractNotifier implements NotifierInterface
{
    protected function renderContentWithFluid(string $content, array $variables): string
    {
        $view = GeneralUtility::makeInstance(ObjectManager::class)->get(StandaloneView::class);
        $view->setTemplateSource($content);
        $view->assignMultiple($variables);
        return $view->render();
    }

    protected function getLanguageService(): LanguageService
    {
        if (!$GLOBALS['LANG'] instanceof LanguageService) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
            $GLOBALS['LANG']->init('default');
        }
        return $GLOBALS['LANG'];
    }
}
