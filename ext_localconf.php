<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'] = [];
}
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_div.php']['systemLog'][] = \Smichaelsen\Noti\Hook\SystemLogHook::class . '->logToNoti';

\Smichaelsen\Noti\EventRegistry::registerEvent(
    (new \Smichaelsen\Noti\Domain\Model\Event(\Smichaelsen\Noti\Hook\SystemLogHook::NOTIFICATION_EVENT_SYSLOG))
        ->setTitle('System Log')
        ->addPlaceholder('msg')
        ->addPlaceholder('extKey')
        ->addPlaceholder('backTrace')
        ->addPlaceholder('severity')
);
