<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$lll = 'LLL:EXT:noti/Resources/Private/Language/locallang_db.xlf:tx_noti_subscription';

return [
    'ctrl' => [
        'title' => $lll,
        'label' => 'event_key',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'iconfile' => 'EXT:noti/Resources/Public/Icons/tx_noti_subscription.svg',
    ],
    'types' => [
        '0' => ['showitem' => 'event_key, notifier_key, user'],
    ],
    'columns' => [
        'event_key' => [
            'label' => $lll . '.event_key',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => \Smichaelsen\Noti\UserFunc\TcaUserFunc::class . '->eventItems',
            ],
        ],
        'notifier_key' => [
            'label' => $lll . '.notifier_key',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => \Smichaelsen\Noti\UserFunc\TcaUserFunc::class . '->notifierItems',
            ],
        ],
        'user' => [
            'label' => $lll . '.user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'be_users',
            ],
        ],
    ],
];
