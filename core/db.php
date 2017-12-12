<?php if (!defined("INITIALIZED")) die();

class DB
{
    public $mysqli;

    private function __construct()
    {
        $conf = (require('conf.php'))['db'];

        $this->mysqli = new mysqli(
            $conf['host'],
            $conf['user'],
            $conf['password'],
            $conf['database']
        );

        if ($error = mysqli_connect_error()) {
            ErrorDie(500);
        }
    }

    private static $_instance = null;
    public static function Instance() 
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function BeginTransaction()
    {
        $this->mysqli->begin_transaction();
    }

    public function CommitTransaction()
    {
        $this->$mysqli->commit();
    }

    public function RellbackTransaction()
    {
        $this->$mysqli->rollback();
    }

    public function Query($sql)
    {

    }
}
