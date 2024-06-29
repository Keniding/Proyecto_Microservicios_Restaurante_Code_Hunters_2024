<?php
namespace apiRest\gestionUser;

use Conexion\Database as Database;
use apiRest\gestionUser\UserModel as User;
use router\Router as Router;
use apiRest\gestionUser\UserController as UserController;

class UserRouter extends Router
{

    function routes(): void
    {
        $router = new Router();
        $db = new Database();
        $user = new User($db);
        $router->addRoute("GET","/allUsers", function() use ($db, $user) {
            $controller = new UserController($user);
            header('Content-Type: application/json');
            echo json_encode($controller->index());
        });

        $router->get("/users", function() use ($user) {
            $controller = new UserController($user);
            header('Content-Type: application/json');
            echo json_encode($controller->index());
        });


        $router->get("/user/{id}", function($id) use ($user) {
            $controller = new UserController($user);
            header('Content-Type: application/json');
            echo json_encode($controller->show($id));
        });

        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/allUsers';
        }

        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $router->dispatch("GET",$request_uri);
    }

}