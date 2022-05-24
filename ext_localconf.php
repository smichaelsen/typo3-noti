<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'] = [];
}
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'][] = \Smichaelsen\Noti\Hook\SystemLogHook::class . '->logToNoti';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1583747569] = [
    'nodeName' => 'notiAvailablePlaceholdersField',
    'priority' => 40,
    'class' => \Smichaelsen\Noti\Backend\Form\AvailablePlaceholdersField::class,
];

$eventRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Smichaelsen\Noti\EventRegistry::class);
$eventRegistry->registerEvent(
    (new \Smichaelsen\Noti\Domain\Model\Event(\Smichaelsen\Noti\Hook\SystemLogHook::NOTIFICATION_EVENT_SYSLOG, 'noti'))
        ->setTitle('System Log')
        ->addPlaceholder('msg')
        ->addPlaceholder('extKey')
        ->addPlaceholder('backTrace')
        ->addPlaceholder('severity')
);
