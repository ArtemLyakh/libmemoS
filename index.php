<?php
define("INITIALIZED", true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/app.php");



App::Instance()->Route('GET', '/qwe/{id}/qwe/{qwe}/', function() {
    var_dump(
        App::Instance()->Request()->Parameter('id'),
        App::Instance()->Request()->Parameter('qwe')
    );  
});

App::Instance()->Route('GET', '/test/', "TestController@Test");

App::Instance()->Route('GET', '/file/', function() {
    App::Instance()->FS()->RegisterFile(md5(rand()));
});



App::Instance()->Route('GET', '/install/', function() {
    App::Instance()->Install();
});


App::Instance()->Resolve();