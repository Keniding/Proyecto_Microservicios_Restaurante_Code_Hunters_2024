<?php

namespace Microservices\MessagesService\Service;

use Database\Database;

class UserChatService
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getUserChats($userId)
    {
        $sql = "SELECT c.* FROM chats c
                JOIN users u ON u.rol_id = c.role_id OR c.type = 'general'
                LEFT JOIN chat_permissions cp ON cp.user_id = u.id AND cp.chat_id = c.id
                WHERE u.id = :userId AND (u.estado = 1 OR c.type = 'restricted' OR cp.id IS NOT NULL)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createChat($name, $type, $roleId = null)
    {
        $sql = "INSERT INTO chats (name, type, role_id) VALUES (:name, :type, :roleId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        $stmt->bindParam(':roleId', $roleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
