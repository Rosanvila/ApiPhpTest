<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/../src/config/routes.php';

// Créer la requête PSR-7
$request = ServerRequest::fromGlobals();

$requestMethod = $request->getMethod();
$path = $request->getUri()->getPath();
$routeKey = "$requestMethod $path";

if (array_key_exists($routeKey, $routes)) {
    try {
        [$controllerName, $methodName] = explode('@', $routes[$routeKey]);
        $controllerClass = "App\\Controller\\$controllerName";

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller class '$controllerClass' not found");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method '$methodName' not found in controller '$controllerClass'");
        }

        $response = $controller->$methodName($request);

        // Créer la réponse PSR-7
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode($response));
    } catch (InvalidArgumentException $e) {
        $response = new Response(400, [], json_encode(['error' => $e->getMessage()]));
    } catch (Exception $e) {
        $response = new Response(500, [], json_encode(['error' => $e->getMessage()]));
    }
} else {
    $response = new Response(404, [], json_encode(['error' => 'Route not found']));
}

// Envoyer la réponse
header('Content-Type: application/json');
echo $response->getBody();