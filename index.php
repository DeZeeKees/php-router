<?php
require "./include.php";
use Lib\Router;

$app = new Router(__DIR__);

//index page
$app->get("", "main.php");
$app->post("", "post.php");

$app->get("/hello", "");

$app->start();
?>