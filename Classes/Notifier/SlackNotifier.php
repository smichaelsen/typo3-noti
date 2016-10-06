<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;
use TYPO3\CMS\Lang\LanguageService;

class SlackNotifier implements NotifierInterface
{

    /**
     * @param Event $event
     * @param array $subscriptionRecord
     * @param string $notificationContent
     * @return void
     */
    public function notify(Event $event, $subscriptionRecord, $notificationContent)
    {
        $channel = empty($subscriptionRecord['slack_channel']) ? '#general' : '#' . ltrim($subscriptionRecord['slack_channel'], '#');
        $data = 'payload=' . urlencode(json_encode([
                'channel' => $channel,
                'icon_emoji' => ':bellhop_bell:',
                'text' => $notificationContent,
                'username' => 'noti',
            ]));

        $ch = curl_init($subscriptionRecord['slack_endpoint']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
