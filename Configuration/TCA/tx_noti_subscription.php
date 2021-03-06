<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$lll = 'LLL:EXT:noti/Resources/Private/Language/locallang_db.xlf:tx_noti_subscription';

return [
    'ctrl' => [
        'title' => $lll,
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'iconfile' => 'EXT:noti/Resources/Public/Icons/tx_noti_subscription.svg',
        'type' => 'type',
        'requestUpdate' => 'event',
    ],
    'types' => [
        '1' => ['showitem' => 'title, event, type, --palette--;' . $lll . '.palette.email;email, --palette--;' . $lll . '.palette.notification;notification'],
        \Smichaelsen\Noti\Notifier\EmailNotifier::class => ['showitem' => 'title, event, type, --palette--;' . $lll . '.palette.email;email, --palette--;' . $lll . '.palette.notification;notification'],
        \Smichaelsen\Noti\Notifier\SlackNotifier::class => ['showitem' => 'title, event, type, --palette--;' . $lll . '.palette.slack;slack, --palette--;' . $lll . '.palette.notification;notification']
    ],
    'palettes' => [
        'email' => [
            'showitem' => 'email_subject, email_from, --linebreak--, addresses',
        ],
        'notification' => [
            'showitem' => 'text, available_placeholders',
        ],
        'slack' => [
            'showitem' => 'slack_endpoint, slack_channel',
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
        'email_from' => [
            'label' => $lll . '.email_from',
            'config' => [
                'type' => 'input',
                'placeholder' => 'notification@noti.org',
            ],
        ],
        'email_subject' => [
            'label' => $lll . '.email_subject',
            'config' => [
                'type' => 'input',
                'placeholder' => 'Leave blank to use the event title as email subject',
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
        'slack_channel' => [
            'label' => $lll . '.slack_channel',
            'config' => [
                'type' => 'input',
                'placeholder' => '#general',
            ],
        ],
        'slack_endpoint' => [
            'label' => $lll . '.slack_endpoint',
            'config' => [
                'type' => 'input',
                'placeholder' => 'You can get your webhook endpoint from your Slack settings'
            ],
        ],
        'text' => [
            'label' => $lll . '.text',
            'config' => [
                'type' => 'text',
            ],
        ],
        'title' => [
            'label' => $lll . '.title',
            'config' => [
                'type' => 'input',
                'eval' => 'required'
            ],
        ],
        'type' => [
            'label' => $lll . '.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$lll . '.type.EmailNotifier', \Smichaelsen\Noti\Notifier\EmailNotifier::class],
                    [$lll . '.type.SlackNotifier', \Smichaelsen\Noti\Notifier\SlackNotifier::class],
                ]
            ],
        ],
    ],
];
