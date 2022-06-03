<?php

return [
    'ajax_user_notifications' => [
        'path' => '/noti/ajax',
        'target' => \Smichaelsen\Noti\Controller\AjaxController::class . '::handleRequest'
    ],
    'user_notifications' => [
        'path' => '/noti/list',
        'target' => \Smichaelsen\Noti\Controller\NotificationsController::class . '::handleRequest'
    ],
    'user_notification_settings' => [
        'path' => '/noti/settings',
        'target' => \Smichaelsen\Noti\Controller\NotificationSettingsController::class . '::handleRequest'
    ],
];
