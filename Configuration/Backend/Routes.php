<?php

return [
    'ajax_user_notifications' => [
        'path' => '/ajax/noti',
        'target' => \Smichaelsen\Noti\Controller\AjaxController::class . '::handleRequest'
    ],
];
