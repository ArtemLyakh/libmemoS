<?php if (!defined("INITIALIZED")) die();

class Util
{
    public static function ErrorDie($code)
    {
        http_response_code($code);
        die();
    }
}