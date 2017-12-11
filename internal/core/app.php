<?php

require_once("db.php");

class Application
{
    private $db;

    public function __construct()
    {
        try {
            $connectionData = require("conf.php");
            $this->db = new Database($connectionData['database']);
        } catch (ConnectionException $ex) {
            http_response_code(500);
            die();
        }
        
    }
}