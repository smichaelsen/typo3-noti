<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

class EmailNotifier extends AbstractNotifier
{

    /**
     * @param Event $event
     * @param array $subscriptionRecord
     * @param array $variables
     */
    public function notify(Event $event, $subscriptionRecord, $variables)
    {
        $addresses = GeneralUtility::trimExplode(',', str_replace("\n", ',', $this->renderContentWithFluid($subscriptionRecord['addresses'], $variables)));
        $addresses = array_filter($addresses, function($address) {
            return GeneralUtility::validEmail($address);
        });
        if (count($addresses)) {
            $subject = empty($subscriptionRecord['email_subject']) ?
                $this->getLanguageService()->sL($event->getTitle()) :
                $this->renderContentWithFluid($subscriptionRecord['email_subject'], $variables);
            $from = empty($subscriptionRecord['email_from']) ? 'notification@noti.org' : $this->renderContentWithFluid($subscriptionRecord['email_from'], $variables);
            $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
            $mailMessage->setFrom($from);
            $mailMessage->setBcc($addresses);
            $mailMessage->setSubject($subject);
            $mailMessage->setBody($this->renderContentWithFluid($subscriptionRecord['text'], $variables));
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
