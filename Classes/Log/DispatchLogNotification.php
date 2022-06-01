<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Log;

use Smichaelsen\Noti\Event\LogEntryNotification;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\ProcessorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DispatchLogNotification implements ProcessorInterface
{
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        $logEntryNotification = new LogEntryNotification();
        $logEntryNotification->setVariables([
            'component' => $logRecord->getComponent(),
            'level' => $logRecord->getLevel(),
            'message' => $logRecord->getMessage(),
        ]);
        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        $eventDispatcher->dispatch($logEntryNotification);
        return $logRecord;
    }
}
