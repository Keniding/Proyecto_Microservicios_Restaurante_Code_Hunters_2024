<?php

namespace Microservices\Modifications;

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
        $app->get('/modifications', function() {
            return $this->handleAllModifications();
        });

        $app->get('/modification/{id}', function($id) {
            return $this->handleModifications($id);
        });

        $app->post('/modification', function() {
            return $this->handleStoreModifications($this->input());
        });

        $app->delete('/modification/{id}', function($id) {
            return $this->handleDeleteModifications($id);
        });
    }

    private function handleAllModifications() {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleModifications($id) {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreModifications(array $input) {
        $controller = new Controller($this->model);

        try {
            $data = [
                'name' => $input['name'] ?? null,
                'category' => $input['category'] ?? null,
            ];

            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDeleteModifications($id) {
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
