<?php

namespace Lib;

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

    function join_path(...$segments) {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }

    function define_route($route_name, $file_name) {

        $route = array(
            "route" => $route_name,
            "filename" => $file_name
        );

        array_push($this->routes, $route);
    }

    function render_notfound($message) {
        http_response_code(404);
        $error_message = $message;
        require $this->base_path . $this->join_path("", "notfound.php");
        return $error_message;
    }

    function start() {

        foreach($this->routes as $route) {
            $file = $this->routes_path . $this->join_path("", $route["filename"]);

            if($route["route"] == $this->uri && !is_file($file)) {
                return $this->render_notfound("file does not exist. <br>" . $file);
            }

            if($route["route"] == $this->uri) {
                require $file;
                return;
            }
        }

        return $this->render_notfound($this->uri);
    }

}