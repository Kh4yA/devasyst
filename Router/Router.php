<?php

namespace App\Router;

use ReflectionClass;
use App\Exceptions\RouteNotFound;

/**
 * Class Router
 * class est responsable de l'enregistrement des routes et de la résolution des URI pour exécuter les actions associées.
 * @package App\Router
 */
class Router
{
    private $routes = [];

    public function __construct()
    {    }
    public function registerControllerRoutes($controller)
    {
        $reflection = new ReflectionClass($controller);
        
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);
            
            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();
                $this->register($route->path, [$controller, $method->getName()]);
            }
        }
    }
    public function register(string $path, $action): void
    {
        if (is_callable($action) || is_array($action)) {
            $this->routes[$path] = $action;
        }
    }
    /**
     * Résout l'URI fournie et exécute l'action associée à la route correspondante.
     * Cette méthode prend l'URI, trouve la route correspondante et exécute l'action. 
     * Si l'action est une fonction callable, elle est exécutée directement.
     * Si l'action est un tableau, elle instancie la classe et appelle la méthode correspondante avec les arguments fournis.
     * @param string $uri L'URI à traiter (par exemple, '/home', '/users/123').
     * @throws RouteNotFound Si aucune route correspondante n'est trouvée pour l'URI.
     * @return mixed Résultat de l'exécution de l'action associée à la route.
     */
    public function resolve(string $uri)
    {
        $path = explode('?', $uri)[0];
        $path = trim($path, '/');
        $path = "/{$path}";

        foreach ($this->routes as $route => $action) {
            $routePattern = preg_replace('/{[^}]+}/', '([^/]+)', $route);
            $routePattern = str_replace('/', '\/', $routePattern);
            if (preg_match('/^' . $routePattern . '$/', $path, $matches)) {
                array_shift($matches);
                if (is_callable($action)) {
                    return call_user_func($action, $matches);
                }
                if (is_array($action) && count($action) === 2) {
                    list($className, $method) = $action;
                    if (class_exists($className) && method_exists($className, $method)) {
                        $class = new $className();
                        return call_user_func_array([$class, $method], $matches);
                    }
                }
            }
        }
        throw new RouteNotFound();
    }
}
