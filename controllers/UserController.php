<?php
use eftec\bladeone\BladeOne;
use Models\User;

class UserController
{
    private $blade;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
    }

    public function login (string $email, string $password)
    {

        $user = User::getUserByEmail($email);
        if ($user) {
            $validated = $user->validateCredentials($password);
            if ($validated) {;
                $this->dashboard();
            } else {
                echo $this->blade->run("login", ['error' => 'Invalid credentials']);
            }
        } else {
            echo $this->blade->run("login", ['error' => 'Invalid credentials']);
        }
    }


    /**
     * Display the dashboard
     */
    public function dashboard(): void
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

    /**
     * Create an article
     * @param string $title
     * @param string $content
     */
    public function createArticle(string $title, string $content): void
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
        
        $this->dashboard();
    }
}