<?php

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmailNotifier extends AbstractNotifier
{
    public function notify(Event $event, array $subscriptionRecord, array $variables): void
    {
        $notificationContent = trim($this->renderContentWithFluid($subscriptionRecord['text'], $variables));
        if (empty($notificationContent)) {
            return;
        }
        $addresses = GeneralUtility::trimExplode(',', str_replace("\n", ',', $this->renderContentWithFluid($subscriptionRecord['addresses'], $variables)));
        $addresses = array_filter($addresses, function ($address) {
            return GeneralUtility::validEmail($address);
        });
        if (count($addresses) === 0) {
            return;
        }
        $subject = empty($subscriptionRecord['email_subject']) ?
            $this->getLanguageService()->sL($event->getTitle()) :
            $this->renderContentWithFluid($subscriptionRecord['email_subject'], $variables);
        $from = $this->getSenderAddress($subscriptionRecord, $variables);
        $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
        $mailMessage->setFrom($from);
        $mailMessage->setBcc($addresses);
        $mailMessage->setSubject($subject);
        $mailMessage->text($notificationContent);
        $mailMessage->send();
    }

    protected function getSubject(array $subscriptionRecord, array $variables, Event $event): string
    {
        if (!empty($subscriptionRecord['email_subject'])) {
            $this->renderContentWithFluid($subscriptionRecord['email_subject'], $variables);
        }
        return $this->getLanguageService()->sL($event->getTitle());
    }

    protected function getSenderAddress(array $subscriptionRecord, array $variables): string
    {
        if (!empty($subscriptionRecord['email_from'])) {
            return $this->renderContentWithFluid($subscriptionRecord['email_from'], $variables);
        }
        if (!empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'])) {
            return $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'];
        }
        return 'no-sender-address-configured@noti.org';
    }
}
