<?php

namespace Microservices\CostumerType;

use Auth\Middleware;
use Database\Database;
use JsonException;
use Router\Router;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $typeCostumer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->typeCostumer = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/costumerTypes', function(Request $request, Response $response) {
            return $this->handleAllCostumerTypes($response);
        });

        $app->get('/costumerType/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleCostumerType($response, $args['id']);
        });

        $app->post('/costumerType', function(Request $request, Response $response) {
            return $this->handleStoreCostumerType($response, $request->getParsedBody());
        });
    }

    private function handleAllCostumerTypes(Response $response): Response
    {
        $controller = new Controller($this->typeCostumer);
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

    private function handleCostumerType(Response $response, $id): Response
    {
        $controller = new Controller($this->typeCostumer);
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

    private function handleStoreCostumerType(Response $response, array $input): Response
    {
        $controller = new Controller($this->typeCostumer);

        try {
            $data = [
                'descripcion' => $input['descripcion'],
                'min' => $input['min'],
                'max' => $input['max']
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
