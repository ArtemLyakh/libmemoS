<?php if (!defined("INITIALIZED")) die();

class Util
{
    public static function ErrorDie(int $code)
    {
        http_response_code($code);
        die();
    }

    public static function GetFullPath(string $path)
    {
        $conf = (require('conf.php'))['app'];

        return $conf['server'] . $path;
    }
}