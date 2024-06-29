<?php
namespace Conexion;

use PDO;
use PDOException;

require_once 'Env.php';

/**
 * @method query(string $string)
 * @method prepare(string $string)
 */
class Database
{
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private string $charset = 'utf8mb4';

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function getConnection(): ?PDO
    {
        $pdo = null;

        $dsn = "mysql:host=$this->host;dbname=$this->db_name;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $this->username, $this->password, $options);
            //echo "Conexión exitosa a la base de datos<br>";
        } catch (PDOException $exception) {
            //echo "Connection error: " . $exception->getMessage() . "<br>";
        }

        return $pdo;
    }
}