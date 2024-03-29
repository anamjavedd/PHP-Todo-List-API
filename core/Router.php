<?php

class Router
{

    public $routes = [
        //regsitering routes for get endpoints
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],

    ];

    public static function load($file)
    {
        //$router -new Router;
        $router = new static;

        // routes.php expects this router variable
        require $file;

        return $router;
    }

    public function get($uri, $controller)
    {


        $this->routes['GET'][$uri] = $controller;


    }

    public function post($uri, $controller)
    {

        $this->routes['POST'][$uri] = $controller;


    }

    public function put($uri, $controller)
    {
        $this->routes['PUT'][$uri] = $controller;
    }

    public function delete($uri, $controller)
    {
        $this->routes['DELETE'][$uri] = $controller;
    }


    public function direct($uri, $requestType)
    {
        var_dump('Requested URI: ' . $uri);

        // Check if the requested URI matches any registered routes
        foreach ($this->routes[$requestType] as $route => $controller) {
            // Convert route pattern to a regular expression
            $pattern = preg_replace('/\/{([^\/]+)}/', '/([^/]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            // Check if the requested URI matches the route pattern
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the first element (full match)

                // Explode the controller string to get controller and method
                $controllerParts = explode('@', $controller);

                // Pass matched parameters to the controller action
                return $this->callAction(
                    $controllerParts[0], // Controller
                    $controllerParts[1], // Method
                    $matches // Matched parameters
                );
            }
        }

        // No matching route found, throw an exception
        throw new Exception('No route defined for this URI');
    }


    public function callAction($controllerName, $action, $parameters = [])
    {
        // Create an instance of the controller
        $controller = new $controllerName;

        // Check if the action method exists in the controller
        if (!method_exists($controller, $action)) {
            throw new Exception("{$controllerName} does not respond to the {$action} action");
        }

        // Call the action method with parameters
        return call_user_func_array([$controller, $action], $parameters);
    }

}