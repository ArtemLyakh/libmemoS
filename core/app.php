<?php if (!defined("INITIALIZED")) die();

class App
{
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
        $this->IncludeAppFiles();
    }

    private function IncludeAppFiles()
    {
        $conf = (require('conf.php'))['app'];

        self::RecursivePhpInclude($conf['controllers']);
        self::RecursivePhpInclude($conf['models']);
        self::RecursivePhpInclude($conf['views']);
    }

    private static function RecursivePhpInclude($path)
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file) {
            include_once($file[0]);
        }
    }


    private static function ParseCallable($func)
    {
        if (is_string($func)) {
            $matches = null;
            if (preg_match('/^(\w+)\@(\w+)$/', $func, $matches)) {
                return array($matches[1], $matches[2]);
            }
        }

        return false;
    }


    private $events = array();
    public function Event($name, $func)
    {
        $this->events[] = array(
            'name' => $name,
            'func' => $func
        );
    }

    public function ExecuteEvent($name, $params)
    {
        $functions = array();
        foreach ($this->events as $event) {
            if ($event['name'] == $name) {
                $events[] = $event['func'];
            }
        }

        foreach ($functions as $func) {
            if (is_string($func)) $func = self::ParseCallable($func);
            
            if (is_callable($func)) {
                call_user_func_array($func, $params);
            }
        }
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



    private $user;
    public function User()
    {
        return $this->user;
    }

    private function Auth()
    {
        $this->user = null;

        $headers = getallheaders();
        if (!isset($headers['Authorization'])) return;

        $token = $headers['Authorization'];

        $userId = null;
        try {
            $userId = Token::GetUserIdByToken($token);
        } catch (TokenException $ex) {
            return;
        }

        try {
            $this->user = User::GetById($userId);
        } catch (UserException $ex) {
            return;
        }
    }

    public function Resolve()
    {
        $this->Auth();

        $func = null;
        $params = null;

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] != $method) continue;

            $matches = null;
            if (preg_match($route['path'], $path, $matches)) {
                $func = $route['func'];
                $params = array_slice($matches, 1);
            }
        }

        if (is_string($func)) {
            $func = self::ParseCallable($func);
        } 


        if (!is_callable($func)) Util::ErrorDie(404);

        $code = 200;
        $view = null;
        $raw = null;
        try {
            ob_start();
            $view = call_user_func_array($func, $params);
            $raw = ob_get_clean();
        } catch (AppException $ex) {
            $code = $ex->code;
            $view = new ErrorView($ex->error);
        }
        
        if (!is_null($view) && $view instanceof BaseView) {
            http_response_code($code);
            if ($view->GetType() == 'json') {
                header('Content-Type: application/json');
            }
            echo $view->Get();
        } else {
            echo $raw;
        }

        die();
    }

}


class AppException extends Exception
{
    public $code;
    public $error;

    public function __construct($code, $error)
    {
        $this->code = $code;
        $this->error = $error;
    }
}

