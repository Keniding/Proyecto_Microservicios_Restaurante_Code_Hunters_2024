<?php

namespace Microservices\EstadoMesa;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $estadoMesa;

    public function __construct() {
        parent::__construct();
        $this->estadoMesa = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/estadosMesa', function(Request $request, Response $response) {
            return $this->handleAllEstadosMesa($response);
        });

        $app->get('/estadoMesa/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleEstadoMesa($response, $args['id']);
        });

        $app->post('/estadoMesa', function(Request $request, Response $response) {
            return $this->handleStoreEstadoMesa($request, $response);
        });

        $app->delete('/estadoMesa/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDeleteEstadoMesa($response, $args['id']);
        });
    }

    private function handleAllEstadosMesa(Response $response): Response
    {
        $controller = new Controller($this->estadoMesa);
        try {
            $data = json_encode($controller->index(), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleEstadoMesa(Response $response, $id): Response
    {
        $controller = new Controller($this->estadoMesa);
        try {
            $data = json_encode($controller->show($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreEstadoMesa(Request $request, Response $response): Response
    {
        $controller = new Controller($this->estadoMesa);

        try {
            $input = $request->getParsedBody();

            error_log('Datos recibidos: ' . print_r($input, true));

            $data = [
                'nombre' => $input['nombre'] ?? null,
                'descripcion' => $input['descripcion'] ?? null
            ];

            error_log('Datos a almacenar: ' . print_r($data, true));

            $result = $controller->store($data);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            $response->getBody()->write($success);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleDeleteEstadoMesa(Response $response, mixed $id): Response
    {
        $controller = new Controller($this->estadoMesa);
        try {
            $result = $controller->destroy($id);
            $success = json_encode(['success' => $result], JSON_THROW_ON_ERROR);
            $response->getBody()->write($success);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
