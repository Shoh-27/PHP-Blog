<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqola qo'shish</title>
    <link rel="stylesheet" href="/assets/show.css">
</head>
<body>
    <h2>Yangi maqola qo'shish</h2>
    <form action="../controllers/ArticleController.php" method="POST">
        <label for="title">Sarlavha:</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Maqola matni:</label><br>
        <textarea name="content" id="content" rows="5" required></textarea><br><br>

        <button type="submit">Saqlash</button>
    </form>
    <a href="/views/index.php">Bosh sahifaga qaytish</a>
</body>
</html>
