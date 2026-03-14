<?php

class Router
{
    private $routes = [];

    public function get($route, $controller)
    {
        $this->routes['GET'][$route] = $controller;
    }

    public function post($route, $controller)
    {
        $this->routes['POST'][$route] = $controller;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $route = $_GET['page'] ?? 'home';

        if (isset($this->routes[$method][$route])) {

            $controller = $this->routes[$method][$route];

            list($controllerName, $methodName) = explode('@', $controller);

            require "controllers/$controllerName.php";

            $controllerInstance = new $controllerName();

            call_user_func([$controllerInstance, $methodName]);

        } else {

            require "views/errors/404.php";

        }
    }
}