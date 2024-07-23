<?php

namespace Microservices\Modifications;

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
        $this->model = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/modifications', function(Request $request, Response $response) {
            return $this->handleAllModifications($response);
        });

        $app->get('/modification/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleModifications($response, $args['id']);
        });

        $app->post('/modification', function(Request $request, Response $response) {
            return $this->handleStoreModifications($request, $response);
        });

        $app->delete('/modification/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDeleteModifications($response, $args['id']);
        });
    }

    private function handleAllModifications(Response $response): Response
    {
        $controller = new Controller($this->model);
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

    private function handleModifications(Response $response, $id): Response
    {
        $controller = new Controller($this->model);
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

    private function handleStoreModifications(Request $request, Response $response): Response
    {
        $controller = new Controller($this->model);

        try {
            $input = $request->getParsedBody();

            error_log('Datos recibidos: ' . print_r($input, true));

            $data = [
                'name' => $input['name'] ?? null,
                'category' => $input['category'] ?? null,
                'color' => $input['color'] ?? null,
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

    private function handleDeleteModifications(Response $response, mixed $id): Response
    {
        $controller = new Controller($this->model);
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
