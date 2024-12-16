<?php
session_start();
require_once 'config/database.php';

try {
    // Oxirgi 5 maqolani olish
    $query = "SELECT articles.id, articles.title, articles.content, articles.created_at, users.name AS author 
              FROM articles 
              JOIN users ON articles.user_id = users.id 
              ORDER BY articles.created_at DESC LIMIT 5";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Xatolik: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Bosh Sahifasi</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1>Blog Loyihasi</h1>
    <nav>
        <a href="home.php">Bosh sahifa</a> |
        <a href="views/index.php">Maqolalar</a> |
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="controllers/logout.php">Chiqish</a>
        <?php else: ?>
            <a href="views/login.php">Kirish</a> |
            <a href="views/register.php">Ro'yxatdan o'tish</a>
        <?php endif; ?>
    </nav>

    <h2>So'nggi Maqolalar</h2>
    <?php if ($articles): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h3><a href="views/show.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title']); ?></a></h3>
                <p><?= nl2br(substr(htmlspecialchars($article['content']), 0, 150)) . '...'; ?></p>
                <small>Muallif: <?= htmlspecialchars($article['author']); ?> | <?= $article['created_at']; ?></small>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Hozircha maqolalar mavjud emas.</p>
    <?php endif; ?>

    <footer>
        <p>&copy; <?= date("Y"); ?> Blog Loyihasi</p>
    </footer>
</body>
</html>
