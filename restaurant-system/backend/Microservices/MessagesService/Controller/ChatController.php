<?php

namespace Microservices\MessagesService\Controller;

use Microservices\MessagesService\Service\ChatService;
use Database\Database;

class ChatController
{
    private ChatService $chatService;

    public function __construct(Database $db)
    {
        $this->chatService = new ChatService($db);
    }

    public function createMessage($userId, $chatId, $content)
    {
        try {
            $message = $this->chatService->createMessage($userId, $chatId, $content);
            echo json_encode($message);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getMessages($chatId, $page, $limit)
    {
        try {
            $messages = $this->chatService->getMessages($chatId, $page, $limit);
            echo json_encode($messages);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getChatsForUser($userId)
    {
        try {
            $chats = $this->chatService->getChatsForUser($userId);
            echo json_encode($chats);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
