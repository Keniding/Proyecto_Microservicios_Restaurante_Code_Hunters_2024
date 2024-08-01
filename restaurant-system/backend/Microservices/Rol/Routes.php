<?php

namespace Microservices\Rol;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $rol;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->rol = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void {
        $app->get('/roles', function() {
            return $this->handleAllRoles();
        });

        $app->get('/rol/{id}', function($id) {
            return $this->handleRol($id);
        });

        $app->post('/rol', function() {
            return $this->handleStoreRol($this->input());
        });
    }

    private function handleAllRoles() {
        $controller = new Controller($this->rol);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleRol($id) {
        $controller = new Controller($this->rol);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreRol(array $input) {
        $controller = new Controller($this->rol);

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion']
            ];
            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function createResponse($status, $body) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo $body;
    }
}
