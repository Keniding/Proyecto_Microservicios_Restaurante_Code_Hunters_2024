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

        $this->db->beginTransaction();

        try {
            $sql = "INSERT INTO messages (user_id, chat_id, content) VALUES (:userId, :chatId, :content)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $stmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, \PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new \Exception("Error al crear el mensaje");
            }

            $messageId = $this->db->lastInsertId();

            $messageSql = "SELECT m.id, m.content, m.created_at, u.username 
                       FROM messages m 
                       JOIN users u ON m.user_id = u.id 
                       WHERE m.id = :messageId";
            $messageStmt = $this->db->prepare($messageSql);
            $messageStmt->bindParam(':messageId', $messageId, \PDO::PARAM_INT);
            $messageStmt->execute();

            $message = $messageStmt->fetch(\PDO::FETCH_ASSOC);

            $this->db->commit();

            return $message;

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new \Exception("Error al crear el mensaje: " . $e->getMessage());
        }
    }

    public function getMessages($chatId, $page, $limit)
    {
        $countSql = "SELECT COUNT(*) FROM messages WHERE chat_id = :chatId";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
        $countStmt->execute();
        $totalMessages = $countStmt->fetchColumn();

        $offset = ($page - 1) * $limit;

        if ($offset >= $totalMessages) {
            return [];
        }

        $sql = "SELECT m.*, u.username FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.chat_id = :chatId
            ORDER BY m.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':chatId', $chatId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $messages = $stmt->fetchAll();

        return array_reverse($messages);
    }

    public function getChatsForUser($userId)
    {
        $sql = "SELECT c.* FROM chats c
            JOIN users u ON u.rol_id = c.role_id OR c.type = 'general'
            LEFT JOIN chat_permissions cp ON cp.user_id = u.id AND cp.chat_id = c.id
            WHERE u.id = :userId AND (u.estado = 1 OR c.type = 'restricted' OR cp.id IS NOT NULL)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}