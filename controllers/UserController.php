<?php
require_once '../models/User.php';
require_once '../vendor/autoload.php';

use eftec\bladeone\BladeOne;

class UserController
{
    private $blade;

    public function __construct()
    {
        $views = __DIR__ . '/views';
        $cache = __DIR__ . '/cache';
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
   
        $user = User::getAuthenticatedUser();
        $page = $_GET['page'] ?? 1;
        $articlesPerPage = 10;
        $articles = $user->articles($page, $articlesPerPage);

        echo $this->blade->run("dashboard", [
            'articles' => $articles,
            'currentPage' => $page,
            'user' => $user
        ]);
    }
}