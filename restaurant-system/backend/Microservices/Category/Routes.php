<?php

namespace Microservices\Category;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $category;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->category = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allCategories', function() {
            return $this->handleAllCategories();
        });

        $app->get('/categories', function() {
            return $this->handleAllCategories();
        });

        $app->get('/category/{id}', function($id) {
            return $this->handleCategory($id);
        });

        $app->post('/category', function() {
            return $this->handleStoreCategory($this->input());
        });

        $app->delete('/category/{id}', function($id) {
            return $this->handleDeleteCategory($id);
        });

        $app->put('/category/{id}', function ($id, $data){
            return $this->handleUpdateCategory($id, $data);
        });
    }

    private function handleAllCategories() {
        $controller = new Controller($this->category);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleCategory($id) {
        $controller = new Controller($this->category);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreCategory(array $input) {
        $controller = new Controller($this->category);

        try {
            $data = [
                'nombre' => $input['nombre'] ?? null
            ];
            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(201, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleDeleteCategory($id) {
        $controller = new Controller($this->category);
        try {
            $result = $controller->destroy($id);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $success);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }


    private function handleUpdateCategory($id, $input)
    {
        $controller = new Controller($this->category);
        try {
            $data = [
                'nombre' => $input['nombre'] ?? null
            ];
            $result = $controller->edit($id, $data);
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
