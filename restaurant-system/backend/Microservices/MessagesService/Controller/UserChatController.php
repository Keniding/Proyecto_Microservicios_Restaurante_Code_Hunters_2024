<?php

namespace Microservices\MessagesService\Controller;

use Microservices\MessagesService\Service\UserChatService;
use Database\Database;

class UserChatController
{
    private UserChatService $userChatService;

    public function __construct(Database $db)
    {
        $this->userChatService = new UserChatService($db);
    }

    public function getUserChats($userId)
    {
        try {
            $chats = $this->userChatService->getUserChats($userId);
            echo json_encode($chats);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createChat($name, $type, $roleId = null)
    {
        try {
            $this->userChatService->createChat($name, $type, $roleId);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
