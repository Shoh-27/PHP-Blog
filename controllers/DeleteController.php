<?php
session_start();
require_once '../config/database.php';

$id = $_GET['id'];

// Maqolani o'chirish
$query = "DELETE FROM articles WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'id' => $id,
    'user_id' => $_SESSION['user_id']
]);

header("Location: ../index.php"); // Maqolalar ro'yxatiga qaytish
exit;
?>
