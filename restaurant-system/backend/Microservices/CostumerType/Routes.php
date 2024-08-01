<?php

namespace Microservices\CostumerType;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $typeCostumer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->typeCostumer = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/costumerTypes', function() {
            return $this->handleAllCostumerTypes();
        });

        $app->get('/costumerType/{id}', function($id) {
            return $this->handleCostumerType($id);
        });

        $app->post('/costumerType', function() {
            return $this->handleStoreCostumerType($this->input());
        });
    }

    private function handleAllCostumerTypes() {
        $controller = new Controller($this->typeCostumer);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleCostumerType($id) {
        $controller = new Controller($this->typeCostumer);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreCostumerType(array $input) {
        $controller = new Controller($this->typeCostumer);

        try {
            $data = [
                'descripcion' => $input['descripcion'],
                'min' => $input['min'],
                'max' => $input['max']
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
