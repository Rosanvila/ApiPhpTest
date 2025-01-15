<?php
require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

$routes = require __DIR__ . '/src/config/routes.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeKey = "$requestMethod $path";

try {
    if (!array_key_exists($routeKey, $routes)) {
        throw new Exception('Route not found', 404);
    }

    [$controllerName, $method] = explode('@', $routes[$routeKey]);
    $controllerClass = "App\\Controller\\$controllerName";
    if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
        throw new Exception('Controller or method not found', 404);
    }

    $controller = new $controllerClass();
    $data = json_decode(file_get_contents('php://input'), true); // Lecture et décodage des données JSON si requête POST, PUT ou DELETE	
    $response = $controller->$method($data); // Passage des données au contrôleur
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(['error' => $e->getMessage()]);
}
