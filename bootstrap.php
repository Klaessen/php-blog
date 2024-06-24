<?php
require 'vendor/autoload.php';
require 'services/Container.php';
require 'services/DatabaseService.php';
require 'controllers/UserController.php';
require 'services/ConfigService.php';

use eftec\bladeone\BladeOne;
use Models\BaseModel;
use Models\User;

$container = Container::getInstance();

// Setting up ConfigService
$container->set(ConfigService::class, function ($c) {
    return new ConfigService();
});

// Setting up DatabaseService
$container->set(DatabaseService::class, function ($c) {
    return new DatabaseService($c->get(ConfigService::class));
});

// Setting up BladeOne
$container->set(BladeOne::class, function ($c) {
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    return new BladeOne($views, $cache, BladeOne::MODE_AUTO);
});

// Setting up UserController
$container->set(UserController::class, function ($c) {
    return new UserController($c->get(BladeOne::class), $c->get(DatabaseService::class));
});

// Automatically resolving BaseModel dependencies
$container->bind(BaseModel::class, function ($container) {
    return new BaseModel($container->make(DatabaseService::class));
});

// Resolving User model
$container->bind(User::class, function ($container) {
    return new User($container->make(DatabaseService::class));
});

$container->bind(User::class, function ($container) {
    return new User($container->make(DatabaseService::class));
});

try {
    session_start([
        'save_handler' => 'redis',
        'save_path' => 'tcp://redis:6379?auth=secret123'
    ]);
} catch (Exception $e) {
    error_log('Session start error: ' . $e->getMessage());
    var_dump($e->getMessage());
    phpinfo(INFO_MODULES);
    die();
}