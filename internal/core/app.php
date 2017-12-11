<?php

require_once('db.php');
require_once('request.php');

class Application
{
    private $conf;
    private $db;
    private $request;

    public function __construct()
    {
        $this->conf = require("conf.php");

        try {
            $connectionData = 
            $this->db = new Database($this->conf['database']);
        } catch (ConnectionException $ex) {
            http_response_code(500);
            die();
        }
    }

    public function DB() 
    {
        return $this->db;
    }

    public function Request()
    {
        return $this->request;
    }

    private $routes = array();
    public function Route($method, $path, callable $func)
    {
        $matches = null;
        preg_match_all('/\{\w+\}/', $path, $matches);

        $matches = $matches[0];

        if (count($matches) > 0) {
            foreach ($matches as $key => $value) {
                $path = str_replace($value, '(\w+)', $path);
            }
        }

        $this->routes[] = array(
            'func' => $func,
            'method' => $method,
            'path' => '~^'.$path.'$~',
            'params' => array_map(function ($el) {
                return preg_replace('/\W/', '', $el);
            }, $matches)
        );
    }

    public function Resolve()
    {
        $this->request = new Request();

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $func = null;
        foreach ($this->routes as $route) {
            if ($route['method'] != $method) continue;

            $matches = null;
            if (preg_match($route['path'], $path, $matches)) {
                $request->SetParameters(array_combine(
                    array_values($route['params']),
                    array_slice($matches, 1)
                ));

                $func = $route['func'];
            }
        }

        if ($func == null) {
            http_response_code(404);
            die();
        }

        call_user_func($func, $this);
    }
}
