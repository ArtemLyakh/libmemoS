<?php if (!defined("INITIALIZED")) die();

abstract class BaseView 
{
    public function GetType()
    {
        return 'json';
    }

    public abstract function Get();
}

abstract class BaseController
{
    protected static function RequestAuth()
    {
        if (is_null(App::Instance()->User())) {
            throw new AppException(401, 'Необходима авторизация');
        }          
    }
}