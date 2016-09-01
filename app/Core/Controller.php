<?php 

namespace App\Core;

class Controller {

    public function index() {
        echo "Default Controller Index";
    }

    protected function view($view, $data = []) {
        if (file_exists(__DIR__ . '/../Views/' . $view . '.php')) {
            require_once __DIR__ . '/../Views/' . $view . '.php';
        } else {
            echo 'View Not Found <br/>';
        }
    }

}