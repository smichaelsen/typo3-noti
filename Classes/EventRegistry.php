<?php
namespace Smichaelsen\Noti;

use Exception;
use Smichaelsen\Noti\Domain\Model\Event;
use Smichaelsen\Noti\Service\NotificationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventRegistry
{

    /**
     * @var array
     */
    protected static $eventRegistry = [];

    /**
     * @var NotificationService
     */
    protected static $notificationService;

    /**
     * @param Event $event
     * @throws Exception
     */
    public static function registerEvent(Event $event)
    {
        self::$eventRegistry[$event->getIdentifier()] = $event;
    }

    /**
     * @return Event[]
     */
    public static function getEvents()
    {
        return self::$eventRegistry;
    }

    /**
     * @param string $identifier
     * @param array $variables
     */
    public static function triggerEvent($identifier, $variables = [])
    {
        self::getNotificationService()->notify(self::getEvent($identifier), $variables);
    }

    /**
     * @param string $identifier
     * @return Event
     * @throws Exception
     */
    public static function getEvent($identifier)
    {
        if (!self::hasEvent($identifier)) {
            throw new \Exception('Event ' . $identifier() . ' is not registered', 1475678573);
        }
        return self::$eventRegistry[$identifier];
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public static function hasEvent($identifier)
    {
        return isset(self::$eventRegistry[$identifier]);
    }

    /**
     * @return NotificationService
     */
    protected static function getNotificationService()
    {
        if (!self::$notificationService instanceof NotificationService) {
            self::$notificationService = GeneralUtility::makeInstance(NotificationService::class);
        }
        return self::$notificationService;
    }

}
