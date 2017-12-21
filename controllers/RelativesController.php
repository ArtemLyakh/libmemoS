<?php if (!defined("INITIALIZED")) die();

class AuthController extends BaseController
{
    public static function Add()
    {
        static::RequestAuth();

        $user = App::Instance()->User();

        var_dump($user);

        return;
    }
}