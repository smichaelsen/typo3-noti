<?php
namespace Smichaelsen\Noti\Hook;

use Smichaelsen\Noti\EventRegistry;

class SystemLogHook
{

    const NOTIFICATION_EVENT_SYSLOG = 'syslog';

    /**
     * @param array $params
     */
    public function logToNoti(array $params)
    {
        EventRegistry::triggerEvent(self::NOTIFICATION_EVENT_SYSLOG, $params);
    }

}
