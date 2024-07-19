<?php

namespace Microservices\Category;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $category;

    public function __construct() {
        session_start();
        parent::__construct();
        $this->category = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allCategories', function(Request $request, Response $response) {
            return $this->handleAllCategories($response);
        });

        $app->get('/categories', function(Request $request, Response $response) {
            return $this->handleAllCategories($response);
        });

        $app->get('/category/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleCategory($response, $args['id']);
        });

        $app->post('/category', function(Request $request, Response $response) {
            return $this->handleStoreCategory($request, $response);
        });

        $app->delete('/category/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDeleteCategory($response, $args['id']);
        });
    }

    private function handleAllCategories(Response $response): Response
    {
        $controller = new Controller($this->category);
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

    private function handleCategory(Response $response, $id): Response
    {
        $controller = new Controller($this->category);
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

    private function handleStoreCategory(Request $request, Response $response): Response
    {
        $controller = new Controller($this->category);

        try {
            $input = $request->getParsedBody();

            error_log('Datos recibidos: ' . print_r($input, true));

            $data = [
                'nombre' => $input['nombre'] ?? null
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

    private function handleDeleteCategory(Response $response, mixed $id): Response
    {
        $controller = new Controller($this->category);
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
