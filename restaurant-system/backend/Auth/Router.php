<?php

namespace Auth;

require_once '../Conexion/Database.php';
require_once 'Model.php';
require_once 'Controller.php';

use Database\Database as Database;
use Auth\Model as User;
use Auth\Controller as Controller;
use \Random\RandomException as RandomException;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Router {
    private Controller $authController;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 3600);
            ini_set('session.cookie_lifetime', 3600);
            session_start();
        }
        $database = new Database();
        $db = $database->getConnection();
        $userModel = new User($db);
        $this->authController = new Controller($userModel);
    }

    public function handleRequest(): void
    {
        $request = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($request) {
            case '/api/login':
                if ($method === 'POST') {
                    $this->login();
                }
                break;
            case '/api/logout':
                if ($method === 'POST') {
                    $this->logout();
                }
                break;
            case '/api/protected-endpoint':
                $this->protectedEndpoint();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function login(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        try {
            $response = $this->authController->login($data['dni'], $data['password']);
            echo $response;
        } catch (RandomException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error en la autenticación']);
        }
    }

    private function logout(): void
    {
        echo $this->authController->logout();
    }

    private function protectedEndpoint(): void
    {
        if ($this->authController->checkAuth()) {
            echo json_encode(['status' => 'success', 'message' => 'Acceso permitido']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
        }
    }

    private function notFound(): void
    {
        echo json_encode(['status' => 'error', 'message' => 'Ruta no encontrada']);
    }
}
