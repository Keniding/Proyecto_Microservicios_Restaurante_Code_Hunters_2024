<?php

namespace Microservices\Mesa;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $mesa;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->mesa = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/mesas', function() {
            return $this->handleAllMesas();
        });

        $app->get('/mesa/{id}', function($id) {
            return $this->handleMesa($id);
        });

        $app->get('/mesaForEstado/{id}', function($id) {
            return $this->handleMesaForEstado($id);
        });

        $app->post('/mesa', function() {
            return $this->handleStoreMesa($this->input());
        });
    }

    private function handleAllMesas() {
        $controller = new Controller($this->mesa);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleMesa($id) {
        $controller = new Controller($this->mesa);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleMesaForEstado($id) {
        $controller = new Controller($this->mesa);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreMesa(array $input) {
        $controller = new Controller($this->mesa);

        try {
            $data = [
                'capacidad' => $input['capacidad'],
                'estado' => $input['estado']
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
