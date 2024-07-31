<?php

namespace Microservices\MessagesService\Controller;

use Microservices\MessagesService\Service\ChatService;
use Microservices\MessagesService\Service\PermissionService;
use Microservices\MessagesService\Service\UserChatService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

    public function getMessages(Request $request, Response $response, array $args): Response
    {
        $chatId = $args['chat_id'];
        $page = $request->getQueryParams()['page'] ?? 1;
        $limit = $request->getQueryParams()['limit'] ?? 10;
        $messages = $this->chatService->getMessages($chatId, $page, $limit);
        $response->getBody()->write(json_encode($messages));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createMessage(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        try {
            $result = $this->chatService->createMessage($data['user_id'], $data['chat_id'], $data['content']);
            $response->getBody()->write(json_encode(['success' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    public function getUserChats(Request $request, Response $response, array $args): Response
    {
        $userId = $args['user_id'];
        $chats = $this->userChatService->getUserChats($userId);
        $response->getBody()->write(json_encode($chats));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createChat(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $result = $this->userChatService->createChat($data['name'], $data['type'], $data['role_id'] ?? null);
        $response->getBody()->write(json_encode(['success' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($result ? 201 : 400);
    }

    public function grantChatPermission(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        try {
            $result = $this->permissionService->grantChatPermission($data['admin_id'], $data['user_id'], $data['chat_id']);
            $response->getBody()->write(json_encode(['success' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }
}