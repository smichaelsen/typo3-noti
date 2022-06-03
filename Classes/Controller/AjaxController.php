<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\NullResponse;

class AjaxController extends AbstractBackendController
{
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $notificationUid = (int)$request->getQueryParams()['readNotificationUid'];
        if ($notificationUid === 0) {
            return new NullResponse();
        }
        $this->connection->update(
            'tx_noti_notification',
            ['read' => '1'],
            ['uid' => $notificationUid, 'user' => $this->getBackendUser()->user['uid']]
        );
        return new NullResponse();
    }
}
