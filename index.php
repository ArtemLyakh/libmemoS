<?php
define("INITIALIZED", true);

$app = require_once($_SERVER['DOCUMENT_ROOT'] . "/internal/bootstrap.php");

echo "<pre>";
var_dump($app);
echo "</pre>";
echo "<hr>";
die();