<?php
// =============================================
// Core/Router.php - URL Router
// =============================================

class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void {
        $this->routes['GET'][$path] = [$controller, $method];
    }

    public function post(string $path, string $controller, string $method): void {
        $this->routes['POST'][$path] = [$controller, $method];
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ base path
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . ltrim($uri, '/');
        if ($uri === '') $uri = '/';

        // Tìm route khớp (hỗ trợ params :id)
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controllerName, $action] = $handler;

                $controllerFile = BASE_PATH . "/controllers/{$controllerName}.php";
                if (!file_exists($controllerFile)) {
                    $this->notFound();
                    return;
                }
                require_once $controllerFile;
                $ctrl = new $controllerName();
                $ctrl->$action(...$matches);
                return;
            }
        }

        $this->notFound();
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/views/errors/404.php';
    }
}
