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
            $this->Exit(500);
        }
    }

    private function Exit($code)
    {
        http_response_code($code);
        die();
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
        $request = new Request();

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
            $this->Exit(404);
        }

        call_user_func($func, $request);
    }
}

class Request
{
    public function __construct()
    {

    }

    public function Get($key)
    {
        return $_REQUEST[$key];
    }

    private $params;
    public function SetParameters($params)
    {
        $this->params = $params;
    }
    public function Parameter($key)
    {
        return $this->params[$key];
    }

}