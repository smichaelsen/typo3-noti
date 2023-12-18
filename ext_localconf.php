<?php

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][\Psr\Log\LogLevel::EMERGENCY][\Smichaelsen\Noti\Log\DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][\Psr\Log\LogLevel::ALERT][\Smichaelsen\Noti\Log\DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][\Psr\Log\LogLevel::CRITICAL][\Smichaelsen\Noti\Log\DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][\Psr\Log\LogLevel::ERROR][\Smichaelsen\Noti\Log\DispatchLogNotification::class] = [];

$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1654079048] = \Smichaelsen\Noti\Backend\Toolbar\NotificationCenterToolbarItem::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables']['tx_noti_notification'] = [
    'dateField' => 'tstamp',
    'expirePeriod' => 30,
];

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-noti-bell',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:noti/Resources/Public/Icons/bell.svg')]
);
