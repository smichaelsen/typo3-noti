<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Log;

use Smichaelsen\Noti\Event\LogEntry;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\ProcessorInterface;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DispatchLogNotification implements ProcessorInterface
{
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        if (!in_array($logRecord->getLevel(), array_keys(LogEntry::getAllPossibleVariants())) || !$this->isNewEntry($logRecord)) {
            return $logRecord;
        }
        $logEntryNotification = new LogEntry($logRecord);
        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        $eventDispatcher->dispatch($logEntryNotification);
        return $logRecord;
    }

    private function isNewEntry(LogRecord $logRecord): bool
    {
        $logData = $logRecord->getData();
        if (!isset($logData['exception']) || !$logData['exception'] instanceof \Exception) {
            return true;
        }
        $exception = $logData['exception'];
        $key = 'exceptionCodeNotified_' . $exception->getCode();
        $registry = GeneralUtility::makeInstance(Registry::class);
        if ($registry->get(self::class, $key, false)) {
            return false;
        }
        $registry->set(self::class, $key, true);
        return true;
    }
}
