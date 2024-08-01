<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once 'Router/Router.php';
require_once 'Conexion/Database.php';
require_once 'Middleware/BodyParsingMiddleware.php';

use Database\Database as Database;
use Router\Router;
use Middleware\BodyParsingMiddleware;
use Microservices\Rol\Routes as RolRoutes;
use Microservices\UsoMesa\Routes as UsoMesaRoutes;
use Microservices\User\Routes as UserRoutes;
use Microservices\Costumer\Routes as CostumerRoutes;
use Microservices\CostumerType\Routes as CostumerTypeRoutes;
use Microservices\Factura\Routes  as FacturaRoutes;
use Microservices\Detalle\Routes as DetalleRoutes;
use Microservices\Modifications\Routes as ModificationRoutes;
use Microservices\ModificationsOrders\Routes as ModificationOrderRoutes;
use Microservices\EstadoMesa\Routes as EstadoMesaRoutes;
use Microservices\Mesa\Routes  as MesaRoutes;
use Microservices\Reserva\Routes as ReservationRoutes;
use Microservices\Category\Routes as CategoryRoutes;
use Microservices\Food\Routes as FoodRoutes;
use Microservices\ApiReniec\Routes as ApiReniecRoutes;
use Microservices\MessagesService\Routes\Routes as MessageServiceRoutes;

$db = new Database();

$router = new class extends Router {
    public function __construct() {
        parent::__construct();
    }
};

$router->setPrefix('/api');

$router->addMiddleware(new BodyParsingMiddleware());

(new CategoryRoutes())->registerRoutes($router);
(new FoodRoutes())->registerRoutes($router);
(new RolRoutes())->registerRoutes($router);
(new UserRoutes())->registerRoutes($router);
(new CostumerRoutes())->registerRoutes($router);
(new CostumerTypeRoutes())->registerRoutes($router);
(new FacturaRoutes())->registerRoutes($router);
(new DetalleRoutes())->registerRoutes($router);
(new ModificationRoutes())->registerRoutes($router);
(new ModificationOrderRoutes())->registerRoutes($router);
(new ApiReniecRoutes())->registerRoutes($router);
(new EstadoMesaRoutes())->registerRoutes($router);
(new MesaRoutes())->registerRoutes($router);
(new UsoMesaRoutes())->registerRoutes($router);
(new ReservationRoutes())->registerRoutes($router);
(new MessageServiceRoutes())->registerRoutes($router);

$router->header();
$router->request();
