<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use function FastRoute\simpleDispatcher;
use App\Helpers\RouteHandler;

require __DIR__ . '/../vendor/autoload.php';

// Dispatcher de FastRoute
$dispatcher = simpleDispatcher(require __DIR__ . '/../src/config/routes.php');

// Requête PSR-7
$request = ServerRequest::fromGlobals();

$httpMethod = $request->getMethod();
$uri = $request->getUri()->getPath();

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$routeHandler = new RouteHandler();

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response = new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Route not found']));
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response = new Response(405, ['Content-Type' => 'application/json'], json_encode(['error' => 'Method not allowed']));
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $response = $routeHandler->handle($handler, $vars, $request);
        break;
}

$emitter = new SapiEmitter();
$emitter->emit($response);