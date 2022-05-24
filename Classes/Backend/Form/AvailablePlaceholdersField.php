<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Backend\Form;

use Smichaelsen\Noti\EventRegistry;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AvailablePlaceholdersField extends AbstractFormElement
{
    public function render(): array
    {
        $eventRegistry = GeneralUtility::makeInstance(EventRegistry::class);

        $result = $this->initializeResultArray();
        $row = $this->data['databaseRow'];
        if (empty($row['event'])) {
            $result['html'] = '<p>Save record to view available placeholders.</p>';
            return $result;
        }
        if (is_array($row['event'])) {
            $row['event'] = $row['event'][0];
        }
        $event = $eventRegistry->getEvent($row['event']);
        $availablePlaceholders = $event->getPlaceholders();
        if (count($availablePlaceholders)) {
            $result['html'] = '<p>These placeholders are available to be used in the notification text.</p><dl>';
            foreach ($availablePlaceholders as $placeholder => $description) {
                $result['html'] .= '<dt>{' . $placeholder . '}</dt>';
                if (!empty($description)) {
                    $result['html'] .= '<dd>' . $this->getLanguageService()->sL($description) . '</dd>';
                }
            }
            $result['html'] .= '</dl>';
        } else {
            $result['html'] = '<p>There are no placeholders available for this event type.</p>';
        }
        return $result;
    }
}
