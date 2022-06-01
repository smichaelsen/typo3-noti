<?php

namespace Smichaelsen\Noti\Domain\Model;

class Event
{
    /**
     * @var string
     */
    protected string $extensionKey;

    /**
     * @var string Unique event identifier
     */
    protected string $identifier;

    /**
     * Label or LLL reference
     *
     * @var string
     */
    protected string $title;

    /**
     * @var array
     */
    protected array $placeholders = [];

    public function __construct(string $identifier, string $extensionKey)
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException('The $identifier must not be empty', 1475677768);
        }
        if (empty($extensionKey)) {
            throw new \InvalidArgumentException('The $extensionKey must not be empty', 1478600548);
        }
        $this->extensionKey = $extensionKey;
        $this->identifier = $identifier;
    }

    public function getExtensionKey(): string
    {
        return $this->extensionKey;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return empty($this->title) ? $this->identifier : $this->title;
    }

    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    public function addPlaceholder(string $key, string $description = ''): self
    {
        $this->placeholders[$key] = $description;
        return $this;
    }
}
