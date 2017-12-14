<?php
define("INITIALIZED", true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/bootstrap.php");


App::Instance()->Route('GET', '/api/register/', function() {
    ?>
    <form method="post">
        <input type="email" name="email"><br>
        <input type="password" name="password"><br>
        <input type="password" name="confirm"><br>
        <input type="submit" value="submit">
    </form>
    <?
});

App::Instance()->Route('POST', '/api/register/', 'AuthController@Register');

App::Instance()->Route('GET', '/qwe/{id}/qwe/{qwe}/', function($id, $qwe) {
    echo "<pre>";
    var_dump($id, $qwe);
    echo "</pre>";
    echo "<hr>";

});

App::Instance()->Route('GET', '/qwe/qwe/', function() {
    echo "qwe";
});

App::Instance()->Resolve();