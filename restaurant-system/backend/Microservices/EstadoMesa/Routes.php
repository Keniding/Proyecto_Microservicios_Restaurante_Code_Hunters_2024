<?php

namespace Microservices\EstadoMesa;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $estadoMesa;

    public function __construct() {
        parent::__construct();
        $this->estadoMesa = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/estadosMesa', function() {
            return $this->handleAllEstadosMesa();
        });

        $app->get('/estadoMesa/{id}', function($id) {
            return $this->handleEstadoMesa($id);
        });

        $app->post('/estadoMesa', function() {
            return $this->handleStoreEstadoMesa($this->input());
        });

        $app->delete('/estadoMesa/{id}', function($id) {
            return $this->handleDeleteEstadoMesa($id);
        });
    }

    private function handleAllEstadosMesa() {
        $controller = new Controller($this->estadoMesa);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleEstadoMesa($id) {
        $controller = new Controller($this->estadoMesa);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreEstadoMesa(array $input) {
        $controller = new Controller($this->estadoMesa);

        try {
            $data = [
                'nombre' => $input['nombre'] ?? null,
                'descripcion' => $input['descripcion'] ?? null
            ];
            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDeleteEstadoMesa($id) {
        $controller = new Controller($this->estadoMesa);
        try {
            $result = $controller->destroy($id);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $success);
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
