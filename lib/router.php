<?php

namespace Lib;

/**
 * A basic php router capable of get, post, put, patch and delete requests
 */
class Router {

    private $uri = "";
    private $routes = array();
    private $base_path = "";
    private $routes_path = "";

    function __construct($base_dir) {
        $uriWithoutParams = strtok($_SERVER["REQUEST_URI"], "?");
        $uriWithoutTrailing = rtrim($uriWithoutParams, '/');
        $this->uri = $uriWithoutTrailing;
        $this->routes_path = $base_dir . $this->join_path("", "routes");
        $this->base_path = $base_dir;
    }

    /**
     * A helper function to join paths together
     * @param string[] $segments Set of parameters that will form the path
     * @return string
     */
    function join_path(...$segments) {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }

    /**
     * Defines a router route
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file that is getting routed to.
     * @param string $method The HTTP method the route responds to.
     * @return void
     */
    private function define_route($route_name, $file_name, $method) {
        $file = $this->routes_path . $this->join_path("", strtolower($method), $file_name);

        $route = array(
            "route" => $route_name,
            "filepath" => $file,
            "method" => $method
        );

        array_push($this->routes, $route);
    }

    /**
     * Defines a get route
     * 
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file in the routes/get folder.
     * @return void
     */
    function get($route_name, $file_name) {
        $this->define_route($route_name, $file_name, "GET");
    }

    /**
     * Defines a post route
     * 
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file in the routes/post folder.
     * @return void
     */
    function post($route_name, $file_name) {
        $this->define_route($route_name, $file_name, "POST");
    }

    /**
     * Defines a put route
     * 
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file in the routes/put folder.
     * @return void
     */
    function put($route_name, $file_name) {
        $this->define_route($route_name, $file_name, "PUT");
    }

    /**
     * Defines a patch route
     * 
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file in the routes/patch folder.
     * @return void
     */
    function patch($route_name, $file_name) {
        $this->define_route($route_name, $file_name, "PATCH");
    }

    /**
     * Defines a delete route
     * 
     * @param string $route_name Name of the route.
     * @param string $file_name Name of the file in the routes/delete folder.
     * @return void
     */
    function delete($route_name, $file_name) {
        $this->define_route($route_name, $file_name, "DELETE");
    }

    function render_notfound($message) {
        http_response_code(404);
        $error_message = $message;
        require $this->base_path . $this->join_path("", "notfound.php");
        return $error_message;
    }

    /**
     * Starts the router
     * @return void
     */
    function start() {

        $this->routes = array_filter($this->routes, function($route) {
            return ($route["method"] == $_SERVER["REQUEST_METHOD"]);
        });

        foreach($this->routes as $route) {
            if($route["route"] == $this->uri && !is_file($route["filepath"])) {
                return $this->render_notfound("file does not exist. <br>" . $route["filepath"]);
            }

            if($route["route"] == $this->uri) {
                require $route["filepath"];
                return;
            }
        }

        return $this->render_notfound($this->uri);
    }

}