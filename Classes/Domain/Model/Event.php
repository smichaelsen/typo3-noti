<?php
namespace Smichaelsen\Noti\Domain\Model;

class Event
{

    /**
     * @var string
     */
    protected $extensionKey;

    /**
     * @var string Unique event identifier
     */
    protected $identifier;

    /**
     * Label or LLL reference
     *
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $placeholders = [];

    /**
     * @param string $identifier
     * @param string $extensionKey
     * @throws \InvalidArgumentException
     */
    public function __construct($identifier, $extensionKey)
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

    /**
     * @return string
     */
    public function getExtensionKey()
    {
        return $this->extensionKey;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $title Title or LLL reference
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return empty($this->title) ? $this->identifier : $this->title;
    }

    /**
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * @param string $key
     * @param string $description Description or LLL reference
     * @return $this
     */
    public function addPlaceholder($key, $description = '')
    {
        $this->placeholders[$key] = $description;
        return $this;
    }

}
