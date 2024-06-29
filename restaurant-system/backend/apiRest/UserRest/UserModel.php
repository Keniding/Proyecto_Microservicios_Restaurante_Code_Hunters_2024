<?php
namespace apiRest\UserRest;

use Conexion\Database;

class UserModel
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($dni, $name, $email, $tel, $pass)
    {
        $stmt = $this->db->prepare("INSERT INTO users (dni, username, email, telefono, password) VALUES (:dni, :name, :email, :tel, :pass)");
        $stmt->bindParam(':dni', $dni, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $tel, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $pass, \PDO::PARAM_STR);
        return $stmt->execute();
    }
}