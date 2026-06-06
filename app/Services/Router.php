<?php

namespace App\Services;

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];
    private array $groupMiddleware = [];
    private string $groupPrefix = '';

    public function get(string $path, array|callable $action, string $name = ''): void { $this->addRoute('GET', $path, $action, $name); }
    public function post(string $path, array|callable $action, string $name = ''): void { $this->addRoute('POST', $path, $action, $name); }
    public function group(array $attributes, callable $callback): void
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;
        if (isset($attributes['prefix'])) $this->groupPrefix .= $attributes['prefix'];
        if (isset($attributes['middleware'])) $this->groupMiddleware = array_merge($this->groupMiddleware, (array)$attributes['middleware']);
        $callback($this);
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }
    private function addRoute(string $method, string $path, array|callable $action, string $name = ''): void
    {
        $fullPath = $this->groupPrefix . $path;
        $fullPath = $fullPath === '/' ? $fullPath : rtrim($fullPath, '/');
        $this->routes[] = ['method' => $method, 'path' => $fullPath, 'action' => $action, 'middleware' => $this->groupMiddleware];
    }
    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $uri === '/' ? $uri : rtrim($uri, '/');
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->match($route['path'], $uri, $params)) {
                foreach ($route['middleware'] as $middlewareDef) {
                    $args = [];
                    if (is_array($middlewareDef)) {
                        $class = $middlewareDef[0];
                        $args = array_slice($middlewareDef, 1);
                    } else {
                        $class = $middlewareDef;
                    }
                    $instance = new $class(...$args);
                    if (!$instance->handle()) return;
                }
                $action = $route['action'];
                if (is_callable($action)) { call_user_func_array($action, $params); return; }
                if (is_array($action)) {
                    [$controller, $mName] = $action;
                    if (class_exists($controller)) {
                        $c = new $controller();
                        if (method_exists($c, $mName)) { call_user_func_array([$c, $mName], $params); return; }
                    }
                }
            }
        }
        http_response_code(404); echo "404 Not Found";
    }
    private function match(string $routePath, string $requestUri, &$params): bool
    {
        $routeRegex = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath) . "$#";
        if (preg_match($routeRegex, $requestUri, $matches)) {
            array_shift($matches); $params = $matches; return true;
        }
        return false;
    }
}
