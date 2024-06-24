<?php
namespace Models;

use Models\BaseModel;
use PDO;
use ConfigService;
use DatabaseService;

class User extends BaseModel
{   
    public $id;
    public $name;
    public $password;
    public $email;
    
    protected $table = 'users';
    
    
    public function __construct($id = null, $name = null, $email = null, $password = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     *  Login the user
     * @param int $userId
     */
    public function loginUser(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    /**
     * Logout the user
     */
    public function logoutUser()
    {
        session_destroy();
        session_start();
    }

    /**
     * Get the user ID
     * @return int|null
     */
    public static function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }


    /**
     * Validate the user credentials
     * @param string $password
     * 
     * @return bool
     */
    public function validateCredentials(string $password): bool
    {
        if (password_verify($password, $this->password)) {
            $this->loginUser($this->id);
            return true;
        }
        return false;
    }

    /**
     * Get the authenticated user
     * @return User|null
     */
    public static function getAuthenticatedUser(): User | null
    {
        $userId = $_SESSION['user_id'] ?? null;
        $config = new ConfigService();
        $db = new DatabaseService($config);
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        return new User($user['id'], $user['name'], $user['email'], $user['password']);
    }


    /**
        * Get all articles by the user
        * @param int|null $page
        * @param int $limit
        * @return array
        */
    public function articles($page = null, $limit = 10)
    {
        $userId = $this->id; 
        if ($page === null) {
            // Fetch all articles if no page is specified
            $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id = :userId");
            $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            // Prepare query with user_id as a parameter
            $query = "SELECT * FROM articles WHERE user_id = :userId";
            $params = ['userId' => $userId];
            return $this->paginate($query, $page, $limit, $params);
        }
    }

    /**
     * Create a new article
     * @param string $title
     * @param string $content
     * 
     * @return void
     */
    public function createArticle(string $title, string $content)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (:title, :content, :userId)");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':userId' => $this->id
        ]);
    }

    
    /**
     * Get the user by email
     * @param string $email
     * 
     * @return User|null
     */
    public static function getUserByEmail(string $email): User | null
    {
        $config = new ConfigService();
        $db = new DatabaseService($config);
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ? new User($user['id'], $user['name'], $user['email'], $user['password']) : null;
    }

    public static function find($id): User | null
    {
        $config = new ConfigService();
        $db = new DatabaseService($config);
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ? new User($user['id'], $user['username'], $user['email'], $user['password']) : null;
    }
}