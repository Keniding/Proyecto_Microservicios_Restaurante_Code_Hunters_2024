<?php

namespace Microservices\Factura;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $factura;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->factura = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/facturas', function() {
            return $this->handleAllFacturas();
        });

        $app->get('/factura/{id}', function($id) {
            return $this->handleFactura($id);
        });

        $app->post('/factura', function() {
            return $this->handleStoreFactura($this->input());
        });
    }

    private function handleAllFacturas() {
        $controller = new Controller($this->factura);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleFactura($id) {
        $controller = new Controller($this->factura);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            return $this->createResponse(500, json_encode(['error' => $e->getMessage()]));
        }
    }

    private function handleStoreFactura(array $input) {
        $controller = new Controller($this->factura);

        try {
            $data = [
                'id' => $input['id'],
                'total' => $input['total'],
                'dni' => $input['dni']
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
