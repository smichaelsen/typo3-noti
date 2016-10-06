<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

class EmailNotifier implements NotifierInterface
{

    /**
     * @param Event $event
     * @param array $subscriptionRecord
     * @param string $notificationContent
     */
    public function notify(Event $event, $subscriptionRecord, $notificationContent)
    {
        $addresses = GeneralUtility::trimExplode(',', str_replace("\n", ',', $subscriptionRecord['addresses']));
        $addresses = array_filter($addresses, function($address) {
            return GeneralUtility::validEmail($address);
        });
        if (count($addresses)) {
            $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
            $mailMessage->setFrom('notification@noti.org');
            $mailMessage->setBcc($addresses);
            $mailMessage->setSubject($this->getLanguageService()->sL($event->getTitle()));
            $mailMessage->setBody($notificationContent);
            $mailMessage->send();
        }
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
