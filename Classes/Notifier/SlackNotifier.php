<?php
namespace Smichaelsen\Noti\Notifier;

use Smichaelsen\Noti\Domain\Model\Event;

class SlackNotifier extends AbstractNotifier
{
    /**
     * @param Event $event
     * @param array $subscriptionRecord
     * @param array $variables
     * @return void
     */
    public function notify(Event $event, $subscriptionRecord, $variables)
    {
        $notificationContent = trim($this->renderContentWithFluid($subscriptionRecord['text'], $variables));
        if (empty($notificationContent)) {
            return;
        }
        $channel = empty($subscriptionRecord['slack_channel']) ? '#general' : '#' . ltrim($this->renderContentWithFluid($subscriptionRecord['slack_channel'], $variables), '#');
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
        curl_exec($ch);
        curl_close($ch);
    }
}
