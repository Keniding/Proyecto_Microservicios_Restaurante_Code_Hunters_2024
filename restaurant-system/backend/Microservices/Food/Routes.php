<?php

namespace Microservices\Food;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $food;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->food = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allFoods', function() {
            return $this->handleAllFoods();
        });

        $app->get('/foods', function() {
            return $this->handleAllFoods();
        });

        $app->get('/food/{id}', function($id) {
            return $this->handleFood($id);
        });

        $app->get('/foodForCategory/{id}', function($id) {
            return $this->handleFoodForCategory($id);
        });

        $app->post('/food', function() {
            return $this->handleStoreFood($this->input());
        });
    }

    private function handleAllFoods() {
        $controller = new Controller($this->food);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleFood($id) {
        $controller = new Controller($this->food);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleFoodForCategory($id) {
        $controller = new Controller($this->food);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreFood(array $input) {
        $controller = new Controller($this->food);

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion'],
                'precio' => $input['precio'],
                'disponibilidad' => $input['disponibilidad'],
                'categoria_id' => $input['categoria_id']
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
