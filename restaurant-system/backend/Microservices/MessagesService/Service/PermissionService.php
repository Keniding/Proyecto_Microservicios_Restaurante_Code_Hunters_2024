<?php

namespace Microservices\MessagesService\Service;

use Database\Database;

class PermissionService
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function grantChatPermission($adminId, $userId, $chatId)
    {
        if (!$this->isAdmin($adminId)) {
            throw new \Exception("Only administrators can grant chat permissions.");
        }

        $sql = "INSERT INTO chat_permissions (user_id, chat_id, granted_by) 
                VALUES (:userId, :chatId, :adminId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
        $stmt->bindParam(':adminId', $adminId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function isAdmin($userId): bool
    {
        $sql = "SELECT rol_id FROM users WHERE id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user && $user['rol_id'] == 1;
    }
}
