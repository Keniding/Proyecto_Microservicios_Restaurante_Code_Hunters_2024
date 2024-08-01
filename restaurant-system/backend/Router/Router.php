<?php

namespace Router;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Router implements IRouter
{
    protected array $routes = [];
    protected array $middlewares = [];
    protected string $prefix = '';

    public function __construct()
    {
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function addMiddleware($middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function get($uri, $action, $middleware = null): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post($uri, $action, $middleware = null): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put($uri, $action, $middleware = null): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function delete($uri, $action, $middleware = null): void
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    public function addRoute($method, $uri, $action, $middleware = null): void
    {
        $uri = $this->prefix . rtrim($uri, '/');
        $route = strtoupper($method) . ' ' . rtrim($uri, '/');
        $this->routes[$route] = ['action' => $action, 'middleware' => $middleware];
    }

    public function dispatch($method, $uri)
    {
        $request = ServerRequest::fromGlobals();
        $response = new Response();

        foreach ($this->middlewares as $middleware) {
            $response = call_user_func($middleware, $request, $response);
        }

        $route = strtoupper($method) . ' ' . rtrim($uri, '/');

        foreach ($this->routes as $routePattern => $routeInfo) {
            $pattern = preg_replace(
                '/\{[^\}]+\}/',
                '([^\/]+)',
                str_replace('/', '\/', $routePattern)
            );
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $route, $matches)) {
                array_shift($matches);

                if ($routeInfo['middleware']) {
                    $response = call_user_func($routeInfo['middleware'], $request, $response);
                }

                return call_user_func_array($routeInfo['action'], array_merge([$request, $response], $matches));
            }
        }

        $response = $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['error' => 'Route not found']));
        $this->sendResponse($response);
    }

    public function header(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    public function request(): void
    {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request_method = $_SERVER['REQUEST_METHOD'];
        $this->dispatch($request_method, $request_uri);
    }

    public function input()
    {
        return $_POST;
    }

    public function error(): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['error' => 'Invalid JSON input']);
            return;
        }
    }

    protected function sendResponse(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();
    }
}
