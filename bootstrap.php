<?php
require 'vendor/autoload.php';
require 'services/Container.php';
require 'services/DatabaseService.php';
require 'controllers/UserController.php';

use eftec\bladeone\BladeOne;

$container = new Container();

// Setting up DatabaseService
$container->set(DatabaseService::class, function ($c) {
    return new DatabaseService();
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