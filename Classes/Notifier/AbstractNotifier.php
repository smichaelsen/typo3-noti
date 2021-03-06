<?php
namespace Smichaelsen\Noti\Notifier;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Lang\LanguageService;

abstract class AbstractNotifier implements NotifierInterface
{

    /**
     * @param string $content
     * @param array $variables
     * @return string
     */
    protected function renderContentWithFluid($content, $variables)
    {
        $view = GeneralUtility::makeInstance(ObjectManager::class)->get(StandaloneView::class);
        $view->setTemplateSource($content);
        $view->assignMultiple($variables);
        return $view->render();
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        if (!$GLOBALS['LANG'] instanceof LanguageService) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
            $GLOBALS['LANG']->init('default');
        }
        return $GLOBALS['LANG'];
    }

}
