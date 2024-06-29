<?php

namespace router;

class Router implements IRouter
{

    protected array $routes = [];

    public function get($uri, $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    public function addRoute($method, $uri, $action): void
    {
        $route = strtoupper($method) . ' ' . rtrim($uri, '/');
        $this->routes[$route] = $action;
    }

    public function dispatch($method, $uri)
    {
        $route = strtoupper($method) . ' ' . rtrim($uri, '/');

        foreach ($this->routes as $routePattern => $action) {
            $pattern = preg_replace('/\{[^\}]+\}/', '([^\/]+)', str_replace('/', '\/', $routePattern));
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $route, $matches)) {
                array_shift($matches);
                return call_user_func_array($action, $matches);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}