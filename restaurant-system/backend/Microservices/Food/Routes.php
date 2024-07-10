<?php

namespace Microservices\Food;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $food;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->food = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/allFoods', function(Request $request, Response $response) {
            return $this->handleAllFoods($response);
        });

        $app->get('/foods', function(Request $request, Response $response) {
            return $this->handleAllFoods($response);
        });

        $app->get('/food/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleFood($response, $args['id']);
        });

        $app->get('/foodForCategory/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleFoodForCategory($response, $args['id']);
        });

        $app->post('/food', function(Request $request, Response $response) {
            return $this->handleStoreFood($response, $request->getParsedBody());
        });
    }

    private function handleAllFoods(Response $response): Response
    {
        $controller = new Controller($this->food);
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

    private function handleFood(Response $response, $id): Response
    {
        $controller = new Controller($this->food);
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

    private function handleFoodForCategory(Response $response, $id): Response
    {
        $controller = new Controller($this->food);
        try {
            $data = json_encode($controller->showForCategory($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreFood(Response $response, array $input): Response
    {
        $controller = new Controller($this->food);

        try {
            $data = [
                'nombre' => $input['nombre'],
                'descripcion' => $input['descripcion'],
                'precio' => $input['precio'],
                'disponibilidad' => $input['disponibilidad'],
                'categoria_id' => $input['categoria_id']
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
