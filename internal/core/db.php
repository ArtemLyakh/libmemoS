<?php

class Database extends mysqli
{
    private $connection;

    public function __construct(Array $connectionData)
    {
        parent::__construct(
            $connectionData['host'],
            $connectionData['user'],
            $connectionData['password'],
            $connectionData['database']
        );

        if ($error = mysqli_connect_error())
            throw new ConnectionException($error);
    }

    

}

class ConnectionException extends Exception { }