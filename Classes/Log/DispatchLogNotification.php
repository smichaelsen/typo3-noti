<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Log;

use Smichaelsen\Noti\Event\LogEntry;
use TYPO3\CMS\Adminpanel\Service\EventDispatcher;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\ProcessorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DispatchLogNotification implements ProcessorInterface
{
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        if (!in_array($logRecord->getLevel(), array_keys(LogEntry::getAllPossibleVariants()))) {
            return $logRecord;
        }
        $logEntryNotification = new LogEntry($logRecord);
        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        $eventDispatcher->dispatch($logEntryNotification);
        return $logRecord;
    }
}
