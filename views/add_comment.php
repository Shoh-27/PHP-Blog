<?php
// add_comment.php

require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $article_id = $_POST['article_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO comments (article_id, user_id, content) VALUES (:article_id, :user_id, :content)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':content', $content);
    
    if ($stmt->execute()) {
        header("Location: show.php?id=" . $article_id);// Maqolaga qaytish
    } else {
        echo "Xatolik yuz berdi!";
    }
    $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : NULL;
    $query = "INSERT INTO comments (article_id, user_id, content, parent_id) VALUES (:article_id, :user_id, :content, :parent_id)";

}
?>

<form method="POST" action="add_comment.php">
    <textarea name="content" required></textarea>
    <input type="hidden" name="article_id" value="<?= $_GET['id'] ?>">
    <button type="submit">Sharh qoldirish</button>
</form>
