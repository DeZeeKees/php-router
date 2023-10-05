<?php
require "./include.php";
use Lib\Router;

$router = new Router(__DIR__);

//index page
$router->define_route("/", "main.php");
$router->define_route("", "main.php");

$router->define_route("/api/v1", "");

$router->start();
?>