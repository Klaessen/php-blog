<?php
require_once 'BaseModel.php';

class Comment extends BaseModel
{
    public function create($content, $articleId, $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO comments (content, article_id, user_id) VALUES (:content, :article_id, :user_id)");
        $stmt->execute([
            ':content' => $content,
            ':article_id' => $articleId,
            ':user_id' => $userId
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findByArticleId($articleId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id");
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }
}