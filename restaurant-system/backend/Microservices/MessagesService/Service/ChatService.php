<?php

namespace Microservices\MessagesService\Service;

use Database\Database;

class ChatService
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createMessage($userId, $chatId, $content)
    {
        $checkUserSql = "SELECT id FROM users WHERE id = :userId";
        $checkUserStmt = $this->db->prepare($checkUserSql);
        $checkUserStmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $checkUserStmt->execute();

        if ($checkUserStmt->rowCount() == 0) {
            throw new \Exception("El usuario no existe");
        }

        $sql = "INSERT INTO messages (user_id, chat_id, content) VALUES (:userId, :chatId, :content)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getMessages($chatId, $limit = 50)
    {
        $sql = "SELECT m.*, u.username FROM messages m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.chat_id = :chatId 
                ORDER BY m.created_at LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
