<?php
session_start();
require_once '../config/database.php';

// Foydalanuvchi tizimga kirganini va kerakli ma'lumotlarni tekshirish
if (isset($_SESSION['user_id'], $_POST['content'], $_POST['article_id'])) {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content']; // Kommentariya matni
    $article_id = $_POST['article_id']; // Maqola ID

    try {
        // Kommentariya qo'shish
        $query = "INSERT INTO comments (user_id, article_id, content) VALUES (:user_id, :article_id, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        // Yangi kommentariya qo'shilgandan so'ng, maqolani ko'rsatish sahifasiga qaytarish
        header("Location: ../views/show.php?id=" . $article_id);
        exit();
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
} else {
    die("Noto‘g‘ri so‘rov.");
}
?>
