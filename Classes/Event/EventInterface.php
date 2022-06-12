<?php

namespace Smichaelsen\Noti\Event;

interface EventInterface
{
    public function getTitle(): string;

    /**
     * Details about the notification.
     * @return string
     */
    public function getMessage(): string;

    /**
     * Indicated whether the message should be treated as HTML or plain text
     * @return bool
     */
    public function isMessageHtml(): bool;

    /**
     * Hint: The styleguide backend module shows all icons available in the system.
     * @return string
     */
    public function getIconIdentifier(): string;

    /**
     * See getAllPossibleVariants() for an explanation.
     * @return string|null
     */
    public function getVariant(): ?string;

    /**
     * All values that getVariant() can return must be listed here along with a human readable label (or label file reference LLL:EXT:...) .
     * It's used to display subscription options in the notification settings module.
     *
     * Imagine you throw a notification event when a train arrives at the station.
     * But users are maybe not interested regional trains, but only high speed trains. So we want separate subscription options in the notification settings module.
     *
     * So you would return ['regional' => 'Regional train arrives at station', 'high-speed' => 'High speed train arrives at station'].
     *
     * If the event has no variants return ['_default' => 'Train arrives at station']. That will be used when getVariant() returns null.
     * @return array
     */
    public static function getAllPossibleVariants(): array;
}
