<?php

use Lt\Regitra\Storage\MemoryClientRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Lt\Regitra\Controllers\ClientController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// TODO use slim framework way later
$storageFile = __DIR__ . '/../cache/data.txt';
$repository =  MemoryClientRepository::loadRepository($storageFile);
$controller = new ClientController($repository);

$app->get('/', function (Request $request, Response $response, $args) {
    $renderer = new PhpRenderer(__DIR__ . '/../templates');

    $viewData = [
        'name' => 'John',
    ];

    return $renderer->render($response, 'home.php', $viewData);
}) ->setName('home') ;


$app->get('/api/clients', function (Request $request, Response $response, $args) use($controller) {
    $controller->getAll($request, $response, $args);

    return $response;
});

$app->put('/api/clients', function (Request $request, Response $response, $args) use($controller) {
    $controller->add($request, $response, $args);

    return $response;
});

$app->delete('/api/clients', function (Request $request, Response $response, $args)  use($controller) {
    $controller->clear($request, $response, $args);

    return $response;
});


$app->run();

MemoryClientRepository::storeRepository($storageFile, $repository);
