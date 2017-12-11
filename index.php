<?php
define("INITIALIZED", true);

require_once($_SERVER['DOCUMENT_ROOT'] . "/internal/bootstrap.php");

$app = new Application();

$app->Route('GET', '/qwe/{id}/qwe/{qwe}/', function($req) {
    echo "<pre>";
    var_dump($req);
    echo "</pre>";
    echo "<hr>";
});

$app->Route('GET', '/qwe/', function($req) {
    echo "<pre>";
    var_dump($req);
    echo "</pre>";
    echo "<hr>";
});

$app->Resolve();