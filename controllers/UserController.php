<?php
require_once '../models/User.php';

use eftec\bladeone\BladeOne;

class UserController
{
    private $blade;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
    }

    public function dashboard()
    {
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