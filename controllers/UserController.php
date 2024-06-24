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
        $articlesPerPage = 3;
        $articles = $user->articles($page, $articlesPerPage);

        echo $this->blade->run("dashboard", [
            'articles' => $articles,
            'page' => $page,
            'user' => $user,
            'articlesPerPage' => $articlesPerPage,
        ]);
    }

    public function createArticle($title, $content)
    {
        // Validate input
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($title) || empty($content)) {
           echo $this->blade->run("create-article", ['error' => 'Title and content are required']);
            exit();
        }

        if (strlen($content) > 1000) {
            echo $this->blade->run("create-article", ['error' => 'Content is too long']);
            exit();
        }
    
        $user = User::getAuthenticatedUser();
        $user->createArticle($title, $content);
        
        return;
    }
}