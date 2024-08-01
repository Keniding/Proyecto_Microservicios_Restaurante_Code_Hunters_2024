<?php

namespace Microservices\MessagesService\Controller;

use Microservices\MessagesService\Service\PermissionService;
use Database\Database;

class PermissionController
{
    private PermissionService $permissionService;

    public function __construct(Database $db)
    {
        $this->permissionService = new PermissionService($db);
    }

    public function grantPermission($adminId, $userId, $chatId)
    {
        try {
            $this->permissionService->grantChatPermission($adminId, $userId, $chatId);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
