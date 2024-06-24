<?php
require 'vendor/autoload.php';

use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
$userController = $container->get(UserController::class);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo $blade->run("landing");
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /dashboard');
                exit();
            } else {
                echo $blade->run("login", ['error' => 'Invalid credentials']);
            }
        } else {
            if(isset($_SESSION['user_id'])) {
                header('Location: /dashboard');
                exit();
            } else {
                echo $blade->run("login");
            }
        }
        break;
    case '/logout':
        session_destroy();
        header('Location: /');
        exit();
    case '/dashboard':
        $userController->dashboard();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
}