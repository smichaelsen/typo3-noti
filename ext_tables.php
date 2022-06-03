<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'user',
    'notifications',
    'top',
    '',
    [
        'routeTarget' => \Smichaelsen\Noti\Controller\NotificationSettingsController::class . '::handleRequest',
        'access' => 'user,group',
        'name' => 'user_notifications',
        'icon' => 'EXT:noti/Resources/Public/Icons/bell.svg',
        'labels' => 'LLL:EXT:noti/Resources/Private/Language/locallang.xlf',
    ],
);
