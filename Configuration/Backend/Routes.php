<?php

return [
    'ajax_user_notifications' => [
        'path' => '/noti/ajax',
        'target' => \Smichaelsen\Noti\Controller\AjaxController::class . '::processRequest',
    ],
    'user_notifications' => [
        'path' => '/module/noti/list',
        'target' => \Smichaelsen\Noti\Controller\NotificationsController::class . '::processRequest',
    ],
    'user_notification_settings' => [
        'path' => '/module/noti/settings',
        'target' => \Smichaelsen\Noti\Controller\NotificationSettingsController::class . '::processRequest',
    ],
];
