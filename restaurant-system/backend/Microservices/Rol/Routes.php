<?php

namespace Microservices\Rol;

use Auth\Middleware;
use Database\Database;
use JsonException;
use Router\Router;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $rol;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->rol = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allRoles', function(Request $request, Response $response) {
            return $this->handleAllRoles($response);
        });

        $app->get('/roles', function(Request $request, Response $response) {
            return $this->handleAllRoles($response);
        });

        $app->get('/rol/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleRol($response, $args['id']);
        });

        $app->post('/rol', function(Request $request, Response $response) {
            return $this->handleStoreRol($response, $request->getParsedBody());
        });
    }

    private function handleAllRoles(Response $response): Response
    {
        $controller = new Controller($this->rol);
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

    private function handleRol(Response $response, $id): Response
    {
        $controller = new Controller($this->rol);
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

    private function handleStoreRol(Response $response, array $input): Response
    {
        $controller = new Controller($this->rol);

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion']
            ];
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
}
