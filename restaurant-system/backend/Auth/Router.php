<?php

namespace Auth;

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $userModel = new User($db);
            $this->authController = new Controller($userModel);
        } else {
            $this->sendErrorResponse('No se pudo establecer una conexión con la base de datos', 500);
            exit();
        }
    }

    public function handleRequest(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = [
            '/auth/login' => ['POST' => 'login'],
            '/auth/logout' => ['POST' => 'logout'],
            '/auth/protected-endpoint' => ['GET' => 'protectedEndpoint', 'POST' => 'protectedEndpoint']
        ];

        if (isset($routes[$requestUri][$method])) {
            $action = $routes[$requestUri][$method];
            $this->$action();
        } else {
            if (isset($routes[$requestUri])) {
                $this->methodNotAllowed();
            } else {
                $this->notFound();
            }
        }
    }

    private function login(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        try {
            $response = $this->authController->login($data['dni'], $data['password']);
            echo $response;
        } catch (RandomException $e) {
            $this->sendErrorResponse('Error en la autenticación');
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
            $this->sendErrorResponse('No autorizado');
        }
    }

    private function notFound(): void
    {
        $this->sendErrorResponse('Ruta no encontrada', 404);
    }

    private function methodNotAllowed(): void
    {
        $this->sendErrorResponse('Método no permitido', 405);
    }

    private function sendErrorResponse(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}
