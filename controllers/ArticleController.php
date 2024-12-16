<?php
ob_start();
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../views/login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $query = "INSERT INTO articles (user_id, title, content) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id, $title, $content]);

        echo "Maqola muvaffaqiyatli qo'shildi!";
        header('Location: ../views/index.php');
        exit();
    } catch (PDOException $e) {
        die("Xatolik yuz berdi: " . $e->getMessage());
    }
} else {
    echo "Noto'g'ri so'rov.";
}
?>
