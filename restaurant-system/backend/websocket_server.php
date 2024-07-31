<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Microservices\MessagesService\WebSocket\WebSocketHandler;
use Microservices\MessagesService\Service\ChatService;
use Microservices\MessagesService\Service\UserChatService;
use Database\Database;

$db = new Database();
$chatService = new ChatService($db);
$userChatService = new UserChatService($db);

$webSocket = new HttpServer(
    new WsServer(
        new WebSocketHandler($chatService, $userChatService)
    )
);

$server = IoServer::factory(
    $webSocket,
    8081
);

echo "WebSocket server running on port 8081\n";
$server->run();

