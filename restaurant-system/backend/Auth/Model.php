<?php

namespace Auth;

use PDO;

class Model
{
    private $conn;
    private string $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByDNI($dni) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE dni = :dni LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}