<?php
namespace apiRest\UserRest;

use Conexion\Database as Database;
use apiRest\UserRest\UserModel as User;
use JsonException;
use router\Router as Router;
use apiRest\UserRest\UserController as UserController;

/**
 * @method dispatch(string $string, array|false|int|string|null $request_uri)
 * @method addRoute(string $string, string $string1, \Closure $param)
 * @method get(string $string, \Closure $param)
 */
class UserRouter
{
    private Router $router;
    private UserModel $user;

    public function __construct() {
        $this->router = new Router();
        $this->user = new User(new Database());
    }

    public function run(): void
    {
        $this->router->addRoute("GET", "/allUsers", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/users", function() {
            $this->handleAllUsers();
        });

        $this->router->get("/user/{id}", function($id) {
            $this->handleUser($id);
        });

        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/allUsers';
        }

        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->router->dispatch("GET", $request_uri);
    }

    /**
     * @throws JsonException
     */
    private function handleAllUsers(): void
    {
        $controller = new UserController($this->user);
        header('Content-Type: application/json');
        echo json_encode($controller->index(), JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    private function handleUser($id): void
    {
        $controller = new UserController($this->user);
        header('Content-Type: application/json');
        echo json_encode($controller->show($id), JSON_THROW_ON_ERROR);
    }
}

$app = new UserRouter();
$app->run();

