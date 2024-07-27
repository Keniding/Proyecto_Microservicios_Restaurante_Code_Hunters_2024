<?php

namespace Microservices\ApiReniec;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        $app->get('/dniReniec/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDni($response, $args['id']);
        });

    }

    private function handleDni(Response $response, $id): Response
    {
        $controller = new Controller($this->model);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => 'Error al procesar los datos.']);
            $response->getBody()->write($error, $e);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (\Exception $e) {
            $error = json_encode(['error' => 'No existe persona o Error interno del servidor.']);
            $response->getBody()->write($error, $e);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    }

}
