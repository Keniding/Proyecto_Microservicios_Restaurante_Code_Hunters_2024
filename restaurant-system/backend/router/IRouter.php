<?php

namespace router;

interface IRouter
{
    public function get($uri, $action);
    public function post($uri, $action);
    public function put($uri, $action);
    public function delete($uri, $action);
    public function addRoute($method, $uri, $action);
    public function dispatch($method, $uri);

}