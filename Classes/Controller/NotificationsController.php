<?php

declare(strict_types=1);

namespace Smichaelsen\Noti\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;

class NotificationsController extends AbstractBackendController
{
    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        $this->moduleTemplate->setTitle('Notifications');
        $this->generateMenu($request);
        $content = $this->notificationsAction($request);
        $this->moduleTemplate->getView()->assign('content', $content);

        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    private function notificationsAction(ServerRequestInterface $request): string
    {
        if (isset($request->getQueryParams()['markAsRead'])) {
            $this->connection->update(
                'tx_noti_notification',
                ['read' => 1],
                ['user' => (int)$this->getBackendUser()->user['uid']]
            );
        }

        $view = $this->createView($request);
        $notifications = $this->connection->select(
            ['uid', 'title', 'icon_identifier', 'crdate', 'message', 'is_message_html', 'read'],
            'tx_noti_notification',
            ['user' => (int)$this->getBackendUser()->user['uid']],
            [],
            ['crdate' => 'DESC'],
            100
        )->fetchAllAssociative();
        $view->assign('notifications', $notifications);
        $view->assign('markAllReadLink', (string)$this->uriBuilder->buildUriFromRoute('user_notifications', ['markAsRead' => 'all']));
        return $view->render('Notifications');
    }
}
