<?php
namespace Smichaelsen\Noti\Domain\Model;

class Event
{

    /**
     * Extension providing the event
     *
     * @var string
     */
    protected $extensionName;

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
    protected $variables = [];

    /**
     * @param string $extensionName
     * @param string $identifier
     * @throws \InvalidArgumentException
     */
    public function __construct($extensionName, $identifier)
    {
        if (empty($extensionName)) {
            throw new \InvalidArgumentException('The $extensionName must not be empty', 1475677746);
        }
        if (empty($identifier)) {
            throw new \InvalidArgumentException('The $identifier must not be empty', 1475677768);
        }
        $this->extensionName = $extensionName;
        $this->identifier = $identifier;
    }

    /**
     * @param string $title Title or LLL reference
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return $this->extensionName;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param string $key
     * @param string $description Description or LLL reference
     */
    public function addVariable($key, $description)
    {

    }

}
