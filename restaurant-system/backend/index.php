<?php

require_once __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Microservices\Category\Routes as CategoryRoutes;
use Microservices\Food\Routes as FoodRoutes;
use Microservices\Rol\Routes as RolRoutes;
use Microservices\User\Routes as UserRoutes;
use Microservices\Costumer\Routes as CostumerRoutes;
use Microservices\CostumerType\Routes as CostumerTypeRoutes;
use Microservices\Factura\Routes  as FacturaRoutes;
use Microservices\Detalle\Routes as DetalleRoutes;
use Microservices\Modifications\Routes as ModificationRoutes;
use Microservices\ModificationsOrders\Routes as ModificationOrderRoutes;
use Microservices\EstadoMesa\Routes as EstadoMesaRoutes;

use Microservices\ApiReniec\Routes as ApiReniecRoutes;

$psr17Factory = new Psr17Factory();

AppFactory::setResponseFactory($psr17Factory);

$app = AppFactory::create();

$app->addBodyParsingMiddleware(); //Serialization

$app->group('/api', function ($group) {
    (new CategoryRoutes())->registerRoutes($group);
    (new FoodRoutes())->registerRoutes($group);
    (new RolRoutes())->registerRoutes($group);
    (new UserRoutes())->registerRoutes($group);
    (new CostumerRoutes())->registerRoutes($group);
    (new CostumerTypeRoutes())->registerRoutes($group);
    (new FacturaRoutes())->registerRoutes($group);
    (new DetalleRoutes())->registerRoutes($group);
    (new ModificationRoutes())->registerRoutes($group);
    (new ModificationOrderRoutes())->registerRoutes($group);
    (new ApiReniecRoutes())->registerRoutes($group);
    (new EstadoMesaRoutes())->registerRoutes($group);
});

$app->addErrorMiddleware(true, true, true);

$app->run();
