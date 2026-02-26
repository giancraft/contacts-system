<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes[] = ['PUT', $path, $handler];
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes[] = ['DELETE', $path, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        // Strip query string
        $uri = strtok($uri, '?');

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            $pattern = preg_replace('/\{[^}]+\}/', '(\d+)', $routePath);
            $pattern = "#^{$pattern}$#";

            if ($routeMethod === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($handler, array_map('intval', $matches));
                return;
            }
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Rota não encontrada.']);
    }
}
