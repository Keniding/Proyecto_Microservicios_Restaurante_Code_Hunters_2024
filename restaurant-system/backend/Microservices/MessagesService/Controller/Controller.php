<?php

namespace Microservices\MessagesService\Controller;

use Microservices\MessagesService\Service\ChatService;
use Microservices\MessagesService\Service\PermissionService;
use Microservices\MessagesService\Service\UserChatService;

class Controller
{
    private ChatService $chatService;
    private UserChatService $userChatService;
    private PermissionService $permissionService;

    public function __construct(ChatService $chatService, UserChatService $userChatService, PermissionService $permissionService)
    {
        $this->chatService = $chatService;
        $this->userChatService = $userChatService;
        $this->permissionService = $permissionService;
    }

    public function getMessages(array $queryParams, array $args)
    {
        $chatId = $args['chat_id'];
        $page = $queryParams['page'] ?? 1;
        $limit = $queryParams['limit'] ?? 10;
        $messages = $this->chatService->getMessages($chatId, $page, $limit);

        return json_encode($messages);
    }

    public function createMessage(array $data)
    {
        try {
            $result = $this->chatService->createMessage($data['user_id'], $data['chat_id'], $data['content']);
            return json_encode(['success' => $result]);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getUserChats(array $args)
    {
        $userId = $args['user_id'];
        $chats = $this->userChatService->getUserChats($userId);
        return json_encode($chats);
    }

    public function createChat(array $data)
    {
        $result = $this->userChatService->createChat($data['name'], $data['type'], $data['role_id'] ?? null);
        return json_encode(['success' => $result]);
    }

    public function grantChatPermission(array $data)
    {
        try {
            $result = $this->permissionService->grantChatPermission($data['admin_id'], $data['user_id'], $data['chat_id']);
            return json_encode(['success' => $result]);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}