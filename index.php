<?php
define("INITIALIZED", true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/internal/bootstrap.php");

global $app;

$app->Route('GET', '/qwe/{id}/qwe/{qwe}/', function() {
    die("qwe");
});

$app->Route('GET', '/qwe/', function() {
    die('qqq');
});

$app->Resolve();