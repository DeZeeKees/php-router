<?php
require "./include.php";
use Lib\Router;

$app = new Router(__DIR__);

$app->define_global_html_header('<script src="https://unpkg.com/htmx.org@1.9.6"></script>');

//index page
$app->get("", "main.php");
$app->post("", "post.php");

$app->get("/hello", "");

$app->start();
?>