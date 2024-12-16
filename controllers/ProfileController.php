<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Foydalanuvchi ma'lumotlari
    $user_query = "SELECT id, name, email, created_at FROM users WHERE id = :user_id";
    $user_stmt = $pdo->prepare($user_query);
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Foydalanuvchi topilmadi.");
    }

    // Maqolalar
    $articles_query = "SELECT id, title, created_at FROM articles WHERE user_id = :user_id";
    $articles_stmt = $pdo->prepare($articles_query);
    $articles_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $articles_stmt->execute();
    $articles = $articles_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sharhlar
    $comments_query = "SELECT comments.content, comments.created_at, articles.title AS article_title
                       FROM comments
                       JOIN articles ON comments.article_id = articles.id
                       WHERE comments.user_id = :user_id";
    $comments_stmt = $pdo->prepare($comments_query);
    $comments_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $comments_stmt->execute();
    $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Like va dislike bergan sharhlar
    $reactions_query = "SELECT comments.content, comment_reactions.reaction, comments.created_at
                        FROM comment_reactions
                        JOIN comments ON comment_reactions.comment_id = comments.id
                        WHERE comment_reactions.user_id = :user_id";
    $reactions_stmt = $pdo->prepare($reactions_query);
    $reactions_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $reactions_stmt->execute();
    $reactions = $reactions_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Xatolik: " . $e->getMessage());
}

// View'ga o'tish
include '../views/profile.php';
?>
