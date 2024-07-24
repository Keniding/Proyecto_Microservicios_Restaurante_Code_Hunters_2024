<?php

namespace Microservices\Costumer;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $costumer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->costumer = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/costumers', function(Request $request, Response $response) {
            return $this->handleAllCostumers($response);
        });

        $app->get('/costumer/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleCostumer($response, $args['id']);
        });

        $app->get('/costumerForCategory/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleCostumerForCategory($response, $args['id']);
        });

        $app->get('/costumerForDni/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleCostumerForDni($response, $args['id']);
        });

        $app->post('/costumer', function(Request $request, Response $response) {
            return $this->handleStoreCostumer($response, $request->getParsedBody());
        });
    }

    private function handleAllCostumers(Response $response): Response
    {
        $controller = new Controller($this->costumer);
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

    private function handleCostumer(Response $response, $id): Response
    {
        $controller = new Controller($this->costumer);
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

    private function handleCostumerForCategory(Response $response, $id): Response
    {
        $controller = new Controller($this->costumer);
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

    private function handleCostumerForDni(Response $response, $id): Response
    {
        $controller = new Controller($this->costumer);
        try {
            $data = json_encode($controller->showForDni($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreCostumer(Response $response, array $input): Response
    {
        $controller = new Controller($this->costumer);

        try {
            $data = [
                'dni' => $input['dni'],
                'name' => $input['name'],
                'email' => $input['email'],
                'telefono' => $input['telefono'],
                'direccion' => $input['direccion']
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
