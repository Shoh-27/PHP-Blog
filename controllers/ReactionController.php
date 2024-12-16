<?php
session_start();
require_once '../config/database.php';

// Foydalanuvchi kirganligini va POST so'rovini tekshirish
if (isset($_SESSION['user_id'], $_POST['reaction'], $_POST['article_id'])) {
    $user_id = $_SESSION['user_id'];
    $reaction = $_POST['reaction']; // 'like' yoki 'dislike'
    $article_id = $_POST['article_id'];

    // Ruxsat etilgan qiymatlarni tekshirish
    if (!in_array($reaction, ['like', 'dislike'])) {
        die("Noto‘g‘ri reaktsiya turi.");
    }

    try {
        // Foydalanuvchi allaqachon reaksiyalar qoldirganini tekshirish
        $query = "SELECT id FROM article_reactions WHERE user_id = :user_id AND article_id = :article_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Reaktsiyani yangilash
            $update_query = "UPDATE article_reactions SET reaction = :reaction, updated_at = NOW() 
                             WHERE user_id = :user_id AND article_id = :article_id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
            $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $update_stmt->execute();
        } else {
            // Yangi reaktsiya qo'shish
            $insert_query = "INSERT INTO article_reactions (user_id, article_id, reaction, created_at) 
                             VALUES (:user_id, :article_id, :reaction, NOW())";
            $insert_stmt = $pdo->prepare($insert_query);
            $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
            $insert_stmt->execute();
        }

        // Oldingi sahifaga qaytarish
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
} else {
    // Xatolik xabari
    die("Noto‘g‘ri so‘rov.");
}
?>
