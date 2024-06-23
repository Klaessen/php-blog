<?php
try {
    $users = [
        ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT)],
        ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => password_hash('password456', PASSWORD_DEFAULT)],
    ];

    foreach ($users as $user) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute($user);
    }

    $articles = [
        ['title' => 'Introduction to PHP', 'content' => 'This is a beginner-friendly guide to PHP.', 'user_id' => 1],
        ['title' => 'Advanced PHP Techniques', 'content' => 'Learn advanced PHP concepts and techniques.', 'user_id' => 1],
    ];

    foreach ($articles as $article) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (:title, :content, :user_id)");
        $stmt->execute($article);
    }

    $comments = [
        ['user_id' => 1, 'article_id' => 1, 'content' => 'Great article!'],
        ['user_id' => 2, 'article_id' => 1, 'content' => 'I enjoyed reading this.'],
    ];

    foreach ($comments as $comment) {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, article_id, content) VALUES (:user_id, :article_id, :content)");
        $stmt->execute($comment);
    }

    echo "Seeding completed successfully!\n";
} catch (PDOException $e) {
    throw new Exception("Seeding failed: " . $e->getMessage());
}