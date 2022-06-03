<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Service;

class NotifierRegistry
{
    private array $notifiers;

    public function addNotifier(string $notifierClassName, string $label): void
    {
        $this->notifiers[$notifierClassName] = $label;
    }

    public function getNotifiers(): array
    {
        return $this->notifiers;
    }

    public function getAvailableNotifierKeys(): array
    {
        return array_keys($this->notifiers);
    }
}
