<?php
require_once 'BaseModel.php';

class Article extends BaseModel
{
    public function create($title, $content, $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (:title, :content, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':user_id' => $userId
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function paginateArticlesByUserId($userId, $page = 1, $articlesPerPage = 10)
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