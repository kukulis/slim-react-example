<?php

use DI\Container;
use Lt\Regitra\Controllers\ClientController;
use Lt\Regitra\ErrorHandlers\ValidationErrorHandler;
use Lt\Regitra\Storage\ClientRepository;
use Lt\Regitra\Storage\MemoryClientRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set('storageFile', function () {
    return __DIR__ . '/../cache/data.txt';
});
$container->set(ClientRepository::class, function ($c) {
    $storageFile = $c->get('storageFile');
    return MemoryClientRepository::loadRepository($storageFile);
});

$container->set(PhpRenderer::class, function (Container $c) {
    return new PhpRenderer(__DIR__ . '/../templates');
});

$container->set(ClientController::class, function (Container $c) {
    return new ClientController($c->get(ClientRepository::class));
});


// Set container to create App with on AppFactory
//AppFactory::setContainer($container);
$app = AppFactory::createFromContainer($container);

$app->addRoutingMiddleware();

//
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//$errorMiddleware->

$errorHandler = $errorMiddleware->getDefaultErrorHandler();

// TODO reikia atskirti pagal exception'o tipą
//$errorHandler->registerErrorRenderer('text/json', ValidationErrorHandler::class);

$app->get('/', function (Request $request, Response $response, $args) {
    $renderer = $this->get(PhpRenderer::class);

    $viewData = [
        'name' => 'John',
    ];

    return $renderer->render($response, 'home.php', $viewData);
})->setName('home');


$app->get('/api/clients', function (Request $request, Response $response, $args) {
    $controller = $this->get(ClientController::class);
    $controller->getAll($request, $response, $args);

    return $response;
});

// not working ..
//$app->get('/api/clients', ClientController::class. ':getAll');


$app->put('/api/clients', function (Request $request, Response $response, $args) {
    $controller = $this->get(ClientController::class);
    $controller->add($request, $response, $args);

    return $response;
});

$app->delete('/api/clients', function (Request $request, Response $response, $args) {
    $controller = $this->get(ClientController::class);

    $controller->clear($request, $response, $args);

    return $response;
});


$app->run();

MemoryClientRepository::storeRepository(
    $container->get('storageFile'),
    $container->get(ClientRepository::class)
);
