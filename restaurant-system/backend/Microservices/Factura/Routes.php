<?php

namespace Microservices\Factura;

use Auth\Middleware;
use Database\Database;
use JsonException;
use Router\Router;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Routes extends Router
{
    private Model $factura;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->factura = new Model(new Database());
        $this->header();
    }

    public function registerRoutes($app): void
    {
        $app->get('/facturas', function(Request $request, Response $response) {
            return $this->handleAllFacturas($response);
        });

        $app->get('/factura/{id}', function(Request $request, Response $response, array $args) {
            return $this->handleFactura($response, $args['id']);
        });

        $app->post('/factura', function(Request $request, Response $response) {
            return $this->handleStoreFactura($response, $request->getParsedBody());
        });
    }

    private function handleAllFacturas(Response $response): Response
    {
        $controller = new Controller($this->factura);
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

    private function handleFactura(Response $response, $id): Response
    {
        $controller = new Controller($this->factura);
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

    private function handleStoreFactura(Response $response, array $input): Response
    {
        $controller = new Controller($this->factura);

        try {
            $data = [
                'id' => $input['id'],
                'total' => $input['total'],
                'dni' => $input['dni']
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
