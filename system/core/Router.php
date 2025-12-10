<?php
// system/core/Router.php

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(string $method, string $uri, callable|array $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => trim($uri, '/'),
            'action' => $action
        ];
    }

    public function dispatch(): void
    {
        $requestedUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestedMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestedMethod && $route['uri'] === $requestedUri) {
                return $this->executeAction($route['action']);
            }
        }

        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function executeAction(callable|array $action)
    {
        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$controller, $method] = $action;
            $controllerFile = "../../app/controllers/{$controller}.php";

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerObj = new $controller();
                return $controllerObj->$method();
            }
        }

        throw new Exception("Acción inválida en Router");
    }
}
