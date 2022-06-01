<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Event;

use Psr\Log\LogLevel;

class LogEntryNotification implements Notification
{
    public const IDENTIFIER = 'logEntryNotification';

    private array $data;
    private string $title;

    public function __construct(string $title, array $data = [])
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getIconIdentifier(): string
    {
        if (isset($this->data['severity']) && in_array($this->data['severity'], [LogLevel::ALERT, LogLevel::EMERGENCY, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING])) {
            return 'status-dialog-error';
        }
        return 'status-dialog-ok';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVariables(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return 'the message';
    }
}
