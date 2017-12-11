<?php

class Request
{
    private $params;

    public function __construct()
    {

    }

    public function SetParameters($params)
    {
        $this->params = $params;
    }


    
    public function Get($key)
    {
        return $_REQUEST[$key];
    }

    public function Parameter($key)
    {
        return $this->params[$key];
    }

}