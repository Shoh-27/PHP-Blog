<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'], $_POST['reaction'], $_POST['comment_id'], $_POST['article_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment_id = $_POST['comment_id'];
    $reaction = $_POST['reaction']; // like yoki dislike
    $article_id = $_POST['article_id']; // qayta yo‘naltirish uchun

    try {
        // Kommentariyaga reaktsiya berilganini tekshirish
        $query = "SELECT * FROM comment_reactions WHERE user_id = :user_id AND comment_id = :comment_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Reaktsiya mavjud bo‘lsa yangilash
            $update_query = "UPDATE comment_reactions SET reaction = :reaction WHERE user_id = :user_id AND comment_id = :comment_id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
            $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $update_stmt->execute();
        } else {
            // Yangi reaktsiya qo‘shish
            $insert_query = "INSERT INTO comment_reactions (user_id, comment_id, reaction) VALUES (:user_id, :comment_id, :reaction)";
            $insert_stmt = $pdo->prepare($insert_query);
            $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
            $insert_stmt->execute();
        }

        // Qayta yo‘naltirish
        header("Location: ../views/show.php?id=" . $article_id);
        exit();
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
} else {
    die("Noto‘g‘ri so‘rov.");
}
