<?php
require_once '../../../database/db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();
    $sql = "SELECT id, nombre FROM roles";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($roles);
} catch (PDOException $exception) {
    echo json_encode(['error' => $exception->getMessage()]);
}
?>
