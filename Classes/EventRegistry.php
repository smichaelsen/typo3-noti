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
        if (!is_array(self::$eventRegistry[$event->getExtensionName()])) {
            self::$eventRegistry[$event->getExtensionName()] = [];
        }
        if (isset(self::$eventRegistry[$event->getExtensionName()][$event->getIdentifier()])) {
            throw new Exception('Event ' . $event->getExtensionName() . '/' . $event->getIdentifier() . ' was already registered.', 1475677895);
        }
        self::$eventRegistry[$event->getExtensionName()][$event->getIdentifier()] = $event;
    }

    /**
     * @param string $extensionName
     * @param string $identifier
     * @param array $variables
     */
    public static function triggerEvent($extensionName, $identifier, $variables = [])
    {
        self::getNotificationService()->notify(self::getEvent($extensionName, $identifier), $variables);
    }

    /**
     * @param string $extensionName
     * @param string $identifier
     * @return Event
     * @throws Exception
     */
    public static function getEvent($extensionName, $identifier)
    {
        if (!self::hasEvent($extensionName, $identifier)) {
            throw new \Exception('Event ' . $extensionName() . '/' . $identifier() . ' is not registered', 1475678573);
        }
        return self::$eventRegistry[$extensionName][$identifier];
    }

    /**
     * @param string $extensionName
     * @param string $identifier
     * @return bool
     */
    public static function hasEvent($extensionName, $identifier)
    {
        return isset(self::$eventRegistry[$extensionName][$identifier]);
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
