<?php if (!defined("INITIALIZED")) die();

class DB
{
    public $mysqli;

    private function __construct()
    {
        $conf = require('conf.php');
        $connectionData = $conf['db'];

        parent::__construct(
            $connectionData['host'],
            $connectionData['user'],
            $connectionData['password'],
            $connectionData['database']
        );

        if ($error = mysqli_connect_error())
            throw new ConnectionException($error);
    }



    private static $_instance = null;
    public static function Instance() 
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance->$mysqli;
    }

}
