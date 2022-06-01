<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Event;

interface Notification
{
    public function __construct(string $title, array $data);

    public function getIdentifier(): string;

    /**
     * Human readable title or locallang reference
     * @return string
     */
    public function getTitle(): string;

    public function getMessage(): string;

    public function getVariables(): array;
}
