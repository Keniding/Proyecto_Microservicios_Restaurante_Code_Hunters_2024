<?php

namespace Microservices\ModificationsOrders;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/modificationOrders', function() {
            return $this->handleAllModificationOrders();
        });

        $app->get('/modificationOrder/{id}', function($id) {
            return $this->handleModificationOrder($id);
        });

        $app->post('/modificationOrder', function() {
            return $this->handleStoreModificationOrder($this->input());
        });

        $app->delete('/modificationOrder/{id}', function($id) {
            return $this->handleDeleteModificationOrder($id);
        });
    }

    private function handleAllModificationOrders() {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleModificationOrder($id) {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreModificationOrder(array $input) {
        $controller = new Controller($this->model);

        try {
            $data = [
                'order' => $input['order'] ?? null,
                'modification' => $input['modification'] ?? null
            ];

            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDeleteModificationOrder($id) {
        $controller = new Controller($this->model);
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
