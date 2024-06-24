<?php
require_once 'BaseModel.php';

class User extends BaseModel
{
    public $id;
    public $username;
    public $password;
    public $email;
    
    
    public function __construct($id = null, $username = null, $email = null, $password = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function create($name, $email, $password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new self($data['id'], $data['name'], $data['email'], $data['password']);
        }
        return null;
    }

    public function loginUser($userId)
    {
        $_SESSION['user_id'] = $userId;
    }

    public function logoutUser()
    {
        session_destroy();
        session_start();
    }

    public static function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function validateCredentials($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $this->loginUser($user['id']);
            return true;
        }
        return false;
    }

    public static function getAuthenticatedUser()
    {
        $user = new User();
        $userId = $user->getUserId();
        return $userId ? $user->findById($userId) : null;
    }


    /**
        * Get all articles by the user
        * @param int|null $page
        * @param int $limit
        * @return array
        */
    public function articles($page = null, $limit = 10)
    {
        if ($page === null) {
            // Fetch all articles if no page is specified
            $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id = :userId");
            $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            // Prepare query with user_id as a parameter
            $query = "SELECT * FROM articles WHERE user_id = :userId";
            $params = ['userId' => $this->id]; // Pass user_id as parameter
            return $this->paginate($query, $page, $limit, $params);
        }
    }
}