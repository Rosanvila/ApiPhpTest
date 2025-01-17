<?php

namespace App\Helpers;

use Exception;
use InvalidArgumentException;
use GuzzleHttp\Psr7\Response;

class RouteHandler
{
    public function handle($handler, $vars, $request): Response
    {
        [$controllerName, $methodName] = explode('@', $handler);
        $controllerClass = "App\\Controller\\$controllerName";

        if (!class_exists($controllerClass)) {
            return new Response(500, ['Content-Type' => 'application/json'], json_encode(['error' => "Controller class '$controllerClass' not found"]));
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            return new Response(500, ['Content-Type' => 'application/json'], json_encode(['error' => "Method '$methodName' not found in controller '$controllerClass'"]));
        }

        try {
            $result = $controller->$methodName($request, $vars);
            return $result;
        } catch (InvalidArgumentException $e) {
            return new Response(400, ['Content-Type' => 'application/json'], json_encode(['error' => $e->getMessage()]));
        } catch (Exception $e) {
            return new Response(500, ['Content-Type' => 'application/json'], json_encode(['error' => $e->getMessage()]));
        }
    }
}