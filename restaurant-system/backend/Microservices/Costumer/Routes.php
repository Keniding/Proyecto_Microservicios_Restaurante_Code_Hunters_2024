<?php

namespace Microservices\Costumer;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $costumer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->costumer = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/costumers', function() {
            return $this->handleAllCostumers();
        });

        $app->get('/costumer/{id}', function($id) {
            return $this->handleCostumer($id);
        });

        $app->get('/costumerForCategory/{id}', function($id) {
            return $this->handleCostumerForCategory($id);
        });

        $app->get('/costumerForDni/{id}', function($id) {
            return $this->handleCostumerForDni($id);
        });

        $app->post('/costumer', function() {
            return $this->handleStoreCostumer($this->input());
        });
    }

    private function handleAllCostumers() {
        $controller = new Controller($this->costumer);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleCostumer($id) {
        $controller = new Controller($this->costumer);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleCostumerForCategory($id) {
        $controller = new Controller($this->costumer);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleCostumerForDni($id) {
        $controller = new Controller($this->costumer);
        try {
            $data = json_encode($controller->showForDni($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreCostumer(array $input) {
        $controller = new Controller($this->costumer);

        try {
            $data = [
                'dni' => $input['dni'],
                'name' => $input['name'],
                'email' => $input['email'],
                'telefono' => $input['telefono'],
                'direccion' => $input['direccion']
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
