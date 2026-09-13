<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Modules\Users\Repositories\UserRepository;
use App\Support\Router;

UserRepository::class;
$uri = $_SERVER['REQUEST_URI'] ?? '/';
Router::dispatch($uri);
