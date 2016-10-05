<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$lll = 'LLL:EXT:noti/Resources/Private/Language/locallang_db.xlf:tx_noti_subscription';

return [
    'ctrl' => [
        'title' => $lll,
        'label' => 'event',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'iconfile' => 'EXT:noti/Resources/Public/Icons/tx_noti_subscription.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'event, addresses, --palette--;' . $lll . '.palette.notification;notification'],
    ],
    'palettes' => [
        'notification' => [
            'showitem' => 'text, available_placeholders'
        ],
    ],
    'columns' => [
        'addresses' => [
            'label' => $lll . '.addresses',
            'config' => [
                'type' => 'text',
            ],
        ],
        'available_placeholders' => [
            'label' => $lll . '.available_placeholders',
            'config' => [
                'type' => 'user',
                'userFunc' => \Smichaelsen\Noti\UserFunc\SubscriptionTcaUserFunctions::class . '->availablePlaceholdersField',
            ],
        ],
        'event' => [
            'label' => $lll . '.event',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => \Smichaelsen\Noti\UserFunc\SubscriptionTcaUserFunctions::class . '->availableEventsItemsProcFunc',
            ],
        ],
        'text' => [
            'label' => $lll . '.text',
            'config' => [
                'type' => 'text',
            ],
        ],
    ],
];
