<?php if (!defined("INITIALIZED")) die();

require_once('db.php');
require_once('request.php');
require_once('fs.php');

class App
{
    private $db;
    private $request;
    private $fs;

    protected function __clone() {}

    private static $_instance = null;
    public static function Instance() 
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->InitSubsystems();

        $this->IncludeControllers();
    }

    private function InitSubsystems()
    {
        try {
            $this->db = new Database();
        } catch (ConnectionException $ex) {
            http_response_code(500);
            die();
        }

        $this->fs = new FileSystem();
    }

    private function IncludeControllers()
    {
        $conf = (require('conf.php'))['app'];

        foreach(glob($conf['controllers'].'*.php') as $file) {
            include_once($file);
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

    public function FS()
    {
        return $this->fs;
    }






    private $routes = array();
    public function Route($method, $path, $func)
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
                $this->request->SetParameters(array_combine(
                    array_values($route['params']),
                    array_slice($matches, 1)
                ));

                $func = $route['func'];
            }
        }

        if (is_string($func)) {
            $matches = null;
            if (preg_match('/^(\w+)\@(\w+)$/', $func, $matches)) {
                return call_user_func(array($matches[1], $matches[2]));
            }
        } elseif (is_callable($func)) {
            return call_user_func($func);
        }

        http_response_code(404);
        die();
    }




    public function Install()
    {
        $this->FS()->Install();
    }
}
