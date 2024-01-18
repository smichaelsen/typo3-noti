<?php

defined('TYPO3') or die();

use Psr\Log\LogLevel;
use Smichaelsen\Noti\Backend\Toolbar\NotificationCenterToolbarItem;
use Smichaelsen\Noti\Log\DispatchLogNotification;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask;

$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][LogLevel::EMERGENCY][DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][LogLevel::ALERT][DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][LogLevel::CRITICAL][DispatchLogNotification::class] = [];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'][LogLevel::ERROR][DispatchLogNotification::class] = [];

$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1654079048] = NotificationCenterToolbarItem::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][TableGarbageCollectionTask::class]['options']['tables']['tx_noti_notification'] = [
    'dateField' => 'tstamp',
    'expirePeriod' => 30,
];

$currentTypo3Version = VersionNumberUtility::getCurrentTypo3Version();
if (version_compare($currentTypo3Version, '11.5.0', '<')) {
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'ext-noti-bell',
        SvgIconProvider::class,
        ['source' => GeneralUtility::getFileAbsFileName('EXT:noti/Resources/Public/Icons/bell.svg')]
    );
}
