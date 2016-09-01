<?php

// Autoloader PSR-4
require __DIR__ . '/../vendor/autoload.php';

// Use Dependencies
use App\Core\App as App;
use App\Core\Controller as Controller;

// Start the Application
$app = new App;