<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['be_users']['columns']['userMods']['config']['items'] = [
    ['Notification Settings', 'user_notification_settings', 'actions-cog-alt', null, ['title' => 'Notification Settings', 'description' => '']],
];
