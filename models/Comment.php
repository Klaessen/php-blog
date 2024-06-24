<?php
namespace Models;
use Models\BaseModel;

class Comment extends BaseModel
{
    public $content;
    public $id;
    public $user_id;
    public $artcle_id;
    public $created_at;
    protected $table = 'comments';

    public function __construct(string $content, int $id, int $user_id, int $artcle_id, int $created_at)
    {
        parent::__construct();
        $this->content = $content;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->artcle_id = $artcle_id;
        $this->created_at = $created_at;
    }


    public function findByArticleId($articleId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id");
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    public function article()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id');
    }
}