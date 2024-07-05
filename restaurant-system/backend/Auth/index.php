<?php

require_once __DIR__ . '/../vendor/autoload.php';
require 'Router.php';

use Auth\Router;

$router = new Router();
$router->handleRequest();
