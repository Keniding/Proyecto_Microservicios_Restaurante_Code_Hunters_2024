<?php

namespace Microservices\UsoMesa;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $usoMesa;

    public function __construct() {
        parent::__construct();
        $this->usoMesa = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void {
        $app->get('/usosMesa', function() {
            return $this->handleAllUsosMesa();
        });

        $app->get('/usoMesa/{id}', function($id) {
            return $this->handleUsoMesa($id);
        });

        $app->get('/usoMesaForMesa/{id}', function($id) {
            return $this->handleUsoMesaForMesa($id);
        });

        $app->get('/usoMesaForFecha/{id}', function($fecha) {
            return $this->handleUsoMesaForFactura($fecha);
        });

        $app->post('/usoMesa', function() {
            return $this->handleStoreUsoMesa($this->input());
        });
    }

    private function handleAllUsosMesa() {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUsoMesa($id) {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUsoMesaForMesa($id) {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->showForMesa($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleUsoMesaForFactura($fecha) {
        $controller = new Controller($this->usoMesa);
        try {
            $data = json_encode($controller->showForFactura($fecha), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreUsoMesa(array $input) {
        $controller = new Controller($this->usoMesa);

        try {
            $data = [
                'factura_id' => $input['factura_id'],
                'mesa_id' => $input['mesa_id']
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
