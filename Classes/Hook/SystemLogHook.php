<?php
namespace Smichaelsen\Noti\Hook;

use Smichaelsen\Noti\Service\NotificationService;

class SystemLogHook
{
    public const NOTIFICATION_EVENT_SYSLOG = 'syslog';
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function logToNoti(array $params): void
    {
        $this->notificationService->triggerEvent(self::NOTIFICATION_EVENT_SYSLOG, $params);
    }

}
