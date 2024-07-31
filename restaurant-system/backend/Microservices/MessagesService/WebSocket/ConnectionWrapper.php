<?php

namespace Microservices\MessagesService\WebSocket;

use Ratchet\ConnectionInterface;

class ConnectionWrapper
{
    public ConnectionInterface $connection;
    public string $connectionId;

    public function __construct(ConnectionInterface $conn, string $connectionId)
    {
        $this->connection = $conn;
        $this->connectionId = $connectionId;
    }

    public function send($data): void
    {
        $this->connection->send($data);
    }

    public function close(): void
    {
        $this->connection->close();
    }
}