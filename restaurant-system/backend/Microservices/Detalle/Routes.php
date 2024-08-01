<?php

namespace Microservices\Detalle;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $detalle;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->detalle = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/detalles', function() {
            return $this->handleAllDetalles();
        });

        $app->get('/detalle/{id}', function($id) {
            return $this->handleDetalle($id);
        });

        $app->get('/detalleForCategory/{id}', function($id) {
            return $this->handleDetalleForCategory($id);
        });

        $app->get('/detalleForFood/{id}', function($id) {
            return $this->handleDetalleForFood($id);
        });

        $app->post('/detalle', function() {
            return $this->handleStoreDetalle($this->input());
        });
    }

    private function handleAllDetalles() {
        $controller = new Controller($this->detalle);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDetalle($id) {
        $controller = new Controller($this->detalle);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDetalleForCategory($id) {
        $controller = new Controller($this->detalle);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDetalleForFood($id) {
        $controller = new Controller($this->detalle);
        try {
            $data = json_encode($controller->showForFood($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreDetalle(array $input) {
        $controller = new Controller($this->detalle);

        try {
            $data = [
                'factura' => $input['factura'],
                'comida' => $input['comida'],
                'cantidad' => $input['cantidad'],
                'precio' => $input['precio']
            ];
            $result = $controller->store($data);

            if (is_int($result)) {
                $success = json_encode(['success' => true, 'id' => $result], JSON_THROW_ON_ERROR);
            } else {
                $success = json_encode(['success' => false], JSON_THROW_ON_ERROR);
            }

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
