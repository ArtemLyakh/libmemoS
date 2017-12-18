<?php
define("INITIALIZED", true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/bootstrap.php");


App::Instance()->Route('GET', '/api/auth/register/', function() {
    ?>
    <form method="post">
        <input type="email" name="email" placeholder="email"><br>
        <input type="password" name="password" placeholder="password"><br>
        <input type="password" name="confirm" placeholder="confirm"><br>
        <input type="submit" value="submit">
    </form>
    <?
});

App::Instance()->Route('GET', '/api/auth/login/', function() {
    ?>
    <form method="post">
        <input type="email" name="email" placeholder="email"><br>
        <input type="password" name="password" placeholer="password"><br>
        <input type="submit" value="submit">
    </form>
    <?
});


App::Instance()->Route('POST', '/api/auth/register/', 'AuthController@Register');
App::Instance()->Route('POST', '/api/auth/login/', 'AuthController@Login');

App::Instance()->Route('GET', '/api/account/', 'AccountController@GetInfo');
App::Instance()->Route('POST', '/api/account/', 'AccountController@SaveInfo');


App::Instance()->Route('GET', '/test/', function() {
    var_dump(getallheaders());
});

App::Instance()->Resolve();