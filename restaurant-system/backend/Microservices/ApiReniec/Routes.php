<?php

namespace Microservices\ApiReniec;

use Database\Database;
use JsonException;
use Router\Router;

class Routes extends Router
{
    private Model $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Model();
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/dniReniec/{id}', function($id) {
            return $this->handleDni($id);
        });
    }

    private function handleDni($id) {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            return $this->createResponse(200, $data);
        } catch (JsonException $e) {
            $error = json_encode(['error' => 'Error al procesar los datos.']);
            return $this->createResponse(400, $error);
        } catch (\Exception $e) {
            $error = json_encode(['error' => 'No existe persona o Error interno del servidor.']);
            return $this->createResponse(500, $error);
        }
    }

    private function createResponse($status, $body) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo $body;
    }
}
