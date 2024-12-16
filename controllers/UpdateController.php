<?php
session_start();
require_once '../config/database.php';

$id = $_GET['id'];
$title = $_POST['title'];
$content = $_POST['content'];

// Maqolani yangilash
$query = "UPDATE articles SET title = :title, content = :content WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'title' => $title,
    'content' => $content,
    'id' => $id,
    'user_id' => $_SESSION['user_id']
]);

header("Location: ../views/show.php?id=$id"); // Yangilangan maqolani ko'rsatish sahifasiga yo'naltirish
exit;
?>
