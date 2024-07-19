<?php

namespace Microservices\Detalle;

use Database\Database;
use JsonException;
use Router\Router;
use Auth\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $detalle;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->detalle = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/detalles', function(Request $request, Response $response) {
            return $this->handleAllDetalles($response);
        });

        $app->get('/detalle/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDetalle($response, $args['id']);
        });

        $app->get('/detalleForCategory/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDetalleForCategory($response, $args['id']);
        });

        $app->get('/detalleForFood/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleDetalleForFood($response, $args['id']);
        });

        $app->post('/detalle', function(Request $request, Response $response) {
            return $this->handleStoreDetalle($response, $request->getParsedBody());
        });
    }

    private function handleAllDetalles(Response $response): Response
    {
        $controller = new Controller($this->detalle);
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

    private function handleDetalle(Response $response, $id): Response
    {
        $controller = new Controller($this->detalle);
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

    private function handleDetalleForCategory(Response $response, $id): Response
    {
        $controller = new Controller($this->detalle);
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

    private function handleDetalleForFood(Response $response, $id): Response
    {
        $controller = new Controller($this->detalle);
        try {
            $data = json_encode($controller->showForFood($id), JSON_THROW_ON_ERROR);
            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (JsonException $e) {
            $error = json_encode(['error' => $e->getMessage()]);
            $response->getBody()->write($error);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function handleStoreDetalle(Response $response, array $input): Response
    {
        $controller = new Controller($this->detalle);

        try {
            $data = [
                'factura' => $input['factura'],
                'comida' => $input['comida'],
                'cantidad' => $input['cantidad'],
                'precio' => $input['precio']
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
