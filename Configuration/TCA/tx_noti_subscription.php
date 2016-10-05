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
        '1' => ['showitem' => 'event, addresses, text'],
    ],
    'columns' => [
        'addresses' => [
            'label' => $lll . '.addresses',
            'config' => [
                'type' => 'text',
            ],
        ],
        'event' => [
            'label' => $lll . '.event',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Neue Produktbewertung', ''],
                ]
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
