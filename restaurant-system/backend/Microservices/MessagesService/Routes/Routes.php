<?php

namespace Microservices\MessagesService\Routes;

use Microservices\MessagesService\Controller\Controller;
use Microservices\MessagesService\Service\ChatService;
use Microservices\MessagesService\Service\PermissionService;
use Microservices\MessagesService\Service\UserChatService;
use Slim\Routing\RouteCollectorProxy;
use Database\Database;

class Routes
{
    public function __construct()
    {
        $this->header();
    }

    public function registerRoutes(RouteCollectorProxy $group): void
    {
        $db = new Database();
        $chatService = new ChatService($db);
        $userChatService = new UserChatService($db);
        $permissionService = new PermissionService($db);

        $controller = new Controller($chatService, $userChatService, $permissionService);

        $group->get('/chats/{chat_id}/messages', [$controller, 'getMessages']);
        $group->post('/messages', [$controller, 'createMessage']);
        $group->get('/users/{user_id}/chats', [$controller, 'getUserChats']);
        $group->post('/chats', [$controller, 'createChat']);
        $group->post('/chat-permissions', [$controller, 'grantChatPermission']);
    }

    public function header(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
