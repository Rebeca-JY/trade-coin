<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, string $controller, string $function)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'function' => $function,
        ];
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $uri = $this->stripBasePath($uri);

        foreach ($this->routes as $route) {

            // CEK METHOD GET / POST
            if ($method !== $route['method']) {
                continue;
            }

            $pattern = str_replace(
                '{id}',
                '([^/]+)',
                $route['uri']
            );

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {

                require_once __DIR__ . '/../controllers/' . $route['controller'] . '.php';

                array_shift($matches);

                $controllerClass = 'App\\controllers\\' . $route['controller'];

                $controller = new $controllerClass();

                $function = $route['function'];

                call_user_func_array([$controller, $function], $matches);

                return;
            }
        }

        http_response_code(404);

        echo '<h1>404 - Page Not Found</h1>';

    }

    /**
     * Samakan REQUEST_URI dengan pola route (/cart/add) saat app di subfolder Laragon/public.
     */
    private function stripBasePath(string $uri): string
    {
        if (!function_exists('app_base_path')) {
            return $uri === '' ? '/' : $uri;
        }

        $base = app_base_path();
        if ($base !== '' && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base)) ?: '/';
        }

        if ($uri === '' || $uri[0] !== '/') {
            $uri = '/' . ltrim($uri, '/');
        }

        return $uri;
    }
}