<?php

namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Event\EventInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmailNotifier implements NotifierInterface
{
    public function notify(EventInterface $event, int $userId)
    {
        $recipient = $this->getUserEmail($userId);
        if ($recipient === null) {
            return;
        }
        $subject = $event->getTitle();
        $from = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] ?: 'notification@noti.org';
        $mailMessage = GeneralUtility::makeInstance(MailMessage::class);
        $mailMessage->setFrom($from);
        $mailMessage->setTo($recipient);
        $mailMessage->setSubject($subject);
        $mailMessage->html($event->getMessage());
        $mailMessage->send();
    }

    protected function getUserEmail(int $userId): ?string
    {
        $userRecord = BackendUtility::getRecord('be_users', $userId, 'email');
        if (!is_array($userRecord) || !GeneralUtility::validEmail($userRecord['email'])) {
            return null;
        }
        return $userRecord['email'];
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
