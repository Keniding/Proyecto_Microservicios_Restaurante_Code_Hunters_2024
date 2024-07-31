<?php

namespace Microservices\MessagesService\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Microservices\MessagesService\Service\ChatService;
use Microservices\MessagesService\Service\UserChatService;

class WebSocketHandler implements MessageComponentInterface
{
    protected $clients;
    protected $chatService;
    protected $userChatService;
    protected $userConnections = [];
    protected $connections = [];

    public function __construct(ChatService $chatService, UserChatService $userChatService)
    {
        $this->clients = new \SplObjectStorage;
        $this->chatService = $chatService;
        $this->userChatService = $userChatService;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $connectionId = $this->generateConnectionId();
        $wrappedConn = new ConnectionWrapper($conn, $connectionId);
        $this->clients->attach($conn, $wrappedConn);
        $this->connections[$connectionId] = $wrappedConn;
        echo "Nueva conexión! (ID: {$connectionId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $wrappedConn = $this->clients[$from];
        $data = json_decode($msg, true);

        if (!$data) {
            $this->sendError($wrappedConn, "Mensaje inválido");
            return;
        }

        echo "Mensaje recibido:";
        print_r($data);

        switch ($data['type']) {
            case 'auth':
                $this->handleAuth($wrappedConn, $data);
                break;
            case 'chat':
                $this->handleChat($wrappedConn, $data);
                break;
            case 'get_messages':
                $this->handleGetMessages($wrappedConn, $data);
                break;
            case 'other_messages':
                $this->handleOtherMessages($wrappedConn, $data);
                break;
            default:
                $this->sendError($wrappedConn, "Tipo de mensaje desconocido");
        }
    }

    protected function handleAuth($conn, $data)
    {
        if (!isset($data['user_id'])) {
            $this->sendError($conn, "ID de usuario no proporcionado");
            return;
        }

        $userId = $data['user_id'];

        try {
            $this->userConnections[$userId] = $conn->connectionId;
            $conn->userId = $userId;

            $this->sendSuccess($conn, "auth_success", "Usuario autenticado con éxito");
            echo "Usuario $userId autenticado con éxito\n";
        } catch (\Exception $e) {
            $this->sendError($conn, "Error de autenticación: " . $e->getMessage());
        }
    }

    protected function handleChat($from, $data)
    {
        if (!isset($from->userId)) {
            $this->sendError($from, "Usuario no autenticado");
            echo "No autorizado";
            return;
        }

        if (!isset($data['chat_id']) || !isset($data['content'])) {
            $this->sendError($from, "Datos de chat incompletos");
            echo "Datos de chat incompletos datos";
            return;
        }

        try {
            $chatId = $data['chat_id'];

            if (!$this->userChatService->isUserInChat($from->userId, $chatId)) {
                $this->sendError($from, "No tienes permiso para acceder a este chat");
                echo "No tienes permiso para acceder a este chat";
                return;
            }

            $message = $this->chatService->createMessage($from->userId, $chatId, $data['content']);
            $this->sendSuccess($from, "message_sent", "Mensaje enviado con éxito", ['message_id' => $message['id']]);

            $this->broadcastMessage($data['chat_id'], $from->userId, $data['content']);
        } catch (\Exception $e) {
            $this->sendError($from, "Error al crear mensaje: " . $e->getMessage());
            echo "Error al crear mensaje: " . $e->getMessage() . "\n";
        }
    }

    protected function broadcastMessage($chatId, $senderId, $content)
    {
        $message = [
            'type' => 'new_message',
            'chat_id' => $chatId,
            'user_id' => $senderId,
            'content' => $content
        ];

        foreach ($this->connections as $connectionId => $client) {
            if (isset($client->userId)) {
                try {
                    if ($this->userChatService->isUserInChat($client->userId, $chatId)) {
                        $client->send(json_encode($message));
                    }
                } catch (\Exception $e) {
                    echo "Error al verificar el usuario en el chat: " . $e->getMessage() . "\n";
                }
            }
        }
    }

    protected function sendError($conn, $message)
    {
        $conn->send(json_encode([
            'type' => 'error',
            'message' => $message
        ]));
    }

    protected function sendSuccess($conn, $type, $message, $data = [])
    {
        $response = [
            'type' => $type,
            'message' => $message
        ];
        if (!empty($data)) {
            $response = array_merge($response, $data);
        }
        $conn->send(json_encode($response));
    }

    public function onClose(ConnectionInterface $conn)
    {
        $wrappedConn = $this->clients[$conn];
        $this->clients->detach($conn);
        if (isset($wrappedConn->userId)) {
            unset($this->userConnections[$wrappedConn->userId]);
        }
        if (isset($wrappedConn->connectionId)) {
            unset($this->connections[$wrappedConn->connectionId]);
            echo "Conexión {$wrappedConn->connectionId} se ha desconectado\n";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Se ha producido un error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function generateConnectionId()
    {
        return uniqid('conn_');
    }

    protected function handleGetMessages($conn, $data)
    {
        echo "Datos recibidos para obtener mensajes: ";
        print_r($data);

        try {
            $chatId = $data['chat_id'];
            $page = $data['page'];
            $limit = $data['limit'];
            $offset = ($page - 1) * $limit;

            echo "Calculando mensajes: Chat ID: $chatId, Page: $page, Limit: $limit, Offset: $offset\n";

            if (!$this->userChatService->isUserInChat($conn->userId, $chatId)) {
                $this->sendError($conn, "No tienes permiso para acceder a este chat");
                return;
            }

            $messages = $this->chatService->getMessages($chatId, $page, $limit);
            echo "Mensajes obtenidos: " . count($messages) . "\n";
            print_r($messages);

            $response = [
                'type' => $page == 1 ? 'initial_messages' : 'more_messages',
                'messages' => $messages,
                'page' => $page,
                'limit' => $limit
            ];
            $conn->send(json_encode($response));
        } catch (\Exception $e) {
            $this->sendError($conn, "Error al obtener mensajes: " . $e->getMessage());
            echo "Error al obtener mensajes: " . $e->getMessage() . "\n";
        }
    }
}
