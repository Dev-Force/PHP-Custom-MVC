<?php 

namespace App\Core;

use App\Controllers\Home as Home;

class App {

    protected $controller = 'Home';

    protected $method = 'index';

    protected $params = [];

    public function __construct() {   
        
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        $url = $this->parseURL();
        $controller = $this->controller;
        $method = $this->method;

        if (file_exists(__DIR__ . '/../Controllers/' . $url[0] . '.php') && isset($url[0])) {
            $controller = $url[0];
            unset($url[0]);

            $controller = "App\\Controllers\\" . $controller;
            $controller = new $controller;
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) { // Method exists
                    $method = $url[1];
                    unset($url[1]);
                }
            }

        } else if (isset($url[0])) { // Controller does not exist
            http_response_code(404);
            echo "<h1>404 Not Found</h1>";
            echo "The page that you have requested could not be found.";
            exit();
        }

        $this->params = $url ? array_values($url) : [];

        if (is_object($controller)) {
            // Check for matching method in controller (argument checking)
            $check = new \ReflectionMethod($controller, $method);
            if ($check->getNumberOfRequiredParameters() === count($this->params)) {
                $this->controller = $controller;
                $this->method = $method;
            } else {
                $this->controller = "App\\Controllers\\" . $this->controller;
                $this->controller = new $this->controller;
            }
        } else {
            $this->controller = "App\\Controllers\\" . $this->controller;
            $this->controller = new $this->controller;
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseURL() {
        if (isset($_GET['url'])) {
            // return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)); 
            return $url = explode('/', rtrim($_GET['url'], '/'));        
        }
    }
}