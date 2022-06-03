<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\NullResponse;

class AjaxController
{
    private Connection $connection;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connection = $connectionPool->getConnectionForTable('tx_noti_notification');
    }

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

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
