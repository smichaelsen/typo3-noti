<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration']['notice'][\Smichaelsen\Noti\Log\DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1654079048] = \Smichaelsen\Noti\Backend\Toolbar\NotificationCenterToolbarItem::class;

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-noti-bell',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:noti/Resources/Public/Icons/bell.svg')]
);

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
