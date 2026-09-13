<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Support\Router;

$uri = $_SERVER['REQUEST_URI'] ?? '/';
Router::dispatch($uri);
