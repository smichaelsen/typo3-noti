<?php
namespace Smichaelsen\Noti\UserFunc;

use Smichaelsen\Noti\EventRegistry;
use TYPO3\CMS\Lang\LanguageService;

class SubscriptionTcaUserFunctions
{

    /**
     * @param array $parameters
     */
    public function availableEventsItemsProcFunc($parameters)
    {
        foreach (EventRegistry::getEvents() as $eventIdentifier => $event) {
            $parameters['items'][] = [$event->getTitle(), $eventIdentifier];
        }
    }

    /**
     * @param $parameters
     * @return string
     */
    public function availablePlaceholdersField($parameters)
    {
        $row = $this->flattenRecordArray($parameters['row']);
        if (empty($row['event'])) {
            return 'Save record to view available placeholders';
        }
        $event = EventRegistry::getEvent($row['event']);
        $availablePlaceholders = $event->getVariables();
        if (count($availablePlaceholders)) {
            $content = '<p>These placeholders are available to be used in the notification text.</p><dl>';
            foreach ($availablePlaceholders as $placeholder => $description) {
                $content .= '<dt>{' . $placeholder . '}</dt><dd>' . $this->getLanguageService()->sL($description) . '</dd>';
            }
            $content .= '</dl>';
        } else {
            $content = '<p>There are no placeholdes available for this event type.</p>';
        }
        $content .= '<p><i>After changing the event type, save the record to update this information.</i></p>';
        return $content;
    }

    /**
     * some fields are saved in an array, which seems to be a TYPO3 bug
     * @param array $row
     * @return array
     */
    protected function flattenRecordArray($row)
    {
        foreach ($row as $key => $value) {
            if (is_array($value)) {
                $row[$key] = current($value);
            }
        }
        return $row;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}