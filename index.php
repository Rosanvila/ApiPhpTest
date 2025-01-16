<?php

require __DIR__ . '/vendor/autoload.php';

// Charger les routes
$routes = require __DIR__ . '/src/config/routes.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeKey = "$requestMethod $path";

if (array_key_exists($routeKey, $routes)) {
    try {
        [$controllerName, $methodName] = explode('@', $routes[$routeKey]);
        $controllerClass = "App\\Controller\\$controllerName";

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller class '$controllerClass' not found");
        }

        $controller = new $controllerClass();

        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON input');
        }

        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method '$methodName' not found in controller '$controllerClass'");
        }

        $response = $controller->$methodName($input);

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (InvalidArgumentException $e) {
        http_response_code(400); 
        echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
        http_response_code(500); 
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(404); 
    echo json_encode(['error' => 'Route not found']);
}
