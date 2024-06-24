<?php
namespace Models;
use Models\BaseModel;
use PDO;

class Article extends BaseModel
{
    public $id;
    public $title;
    public $content;
    public $user_id;
    public $created_at;

    protected $table = 'articles';

    public function __construct(int $id, string $title, string $content, int $user_id, int $created_at)
    {
        parent::__construct();
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }


    /**
     * Find a Article by user ID
     * 
     * @param int $userId
     * @return array|null
     */
    public function findByUserId(int $userId): array|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Paginate articles by user ID
     * 
     * @param int $userId
     * @param int $page
     * @param int $articlesPerPage
     * @return array|null
     */
    public function paginateArticlesByUserId(int $userId, int $page = 1, int $articlesPerPage = 10): array|null
    {
        $offset = ($page - 1) * $articlesPerPage;
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id = :user_id LIMIT :offset, :limit");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $articlesPerPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}