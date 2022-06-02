<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Event;

use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogRecord;

class LogEntry implements EventInterface
{
    private LogRecord $logRecord;

    public function __construct(LogRecord $logRecord)
    {
        $this->logRecord = $logRecord;
    }

    public function getTitle(): string
    {
        return sprintf(
            'New [%s] level log entry',
            strtoupper($this->logRecord->getLevel())
        );
    }

    public function getMessage(): string
    {
        return $this->logRecord->getComponent() . ': ' . $this->logRecord->getMessage();
    }

    public function getIconIdentifier(): string
    {
        return 'status-dialog-error';
    }

    public function getVariant(): ?string
    {
        return $this->logRecord->getLevel();
    }

    public static function getAllPossibleVariants(): array
    {
        return [
            LogLevel::EMERGENCY => 'Emergency level log entry',
            LogLevel::ALERT => 'Alert level log entry',
            LogLevel::CRITICAL => 'Critical level log entry',
            LogLevel::ERROR => 'Error level log entry',
        ];
    }
}