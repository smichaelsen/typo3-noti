<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$lll = 'LLL:EXT:noti/Resources/Private/Language/locallang_db.xlf:tx_noti_notification';

return [
    'ctrl' => [
        'title' => $lll,
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'iconfile' => 'EXT:noti/Resources/Public/Icons/bell.svg',
    ],
    'types' => [
        '0' => ['showitem' => 'user, read, title, message'],
    ],
    'columns' => [
        'message' => [
            'label' => $lll . '.message',
            'config' => [
                'type' => 'text',
            ],
        ],
        'read' => [
            'label' => $lll . '.read',
            'config' => [
                'type' => 'check',
            ],
        ],
        'title' => [
            'label' => $lll . '.title',
            'config' => [
                'type' => 'input',
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
