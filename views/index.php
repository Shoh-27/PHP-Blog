<?php
session_start();
require_once '../config/database.php';

// Maqolalarni olish
$query = "SELECT articles.*, users.name AS author FROM articles 
          JOIN users ON articles.user_id = users.id 
          ORDER BY articles.created_at DESC";
$stmt = $pdo->query($query);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as &$article) {
    // Like va dislike sonini olish
    $article_id = $article['id'];
    $like_query = "SELECT COUNT(*) FROM article_reactions WHERE article_id = :article_id AND reaction = 'like'";
    $dislike_query = "SELECT COUNT(*) FROM article_reactions WHERE article_id = :article_id AND reaction = 'dislike'";

    $like_stmt = $pdo->prepare($like_query);
    $like_stmt->bindParam(':article_id', $article_id);
    $like_stmt->execute();
    $article['likes'] = $like_stmt->fetchColumn();

    $dislike_stmt = $pdo->prepare($dislike_query);
    $dislike_stmt->bindParam(':article_id', $article_id);
    $dislike_stmt->execute();
    $article['dislikes'] = $dislike_stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh sahifa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php">Mening Profilim</a>
    <?php endif; ?>
    <h1>Blog loyihasi</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Salom, <?= htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <a href="add_article.php">Yangi maqola qo'shish</a> |
        <a href="../controllers/LogoutController.php">Chiqish</a>
    <?php else: ?>
        <a href="login.php">Kirish</a> |
        <a href="register.php">Ro'yxatdan o'tish</a>
    <?php endif; ?>

    <h2>Maqolalar</h2>
    <?php foreach ($articles as $article): ?>
        <div>
            <h3><?= htmlspecialchars($article['title']); ?></h3>
            <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>
            <small>Yozuvchi: <?= htmlspecialchars($article['author']); ?> | <?= $article['created_at']; ?></small><br>

            <a href="show.php?id=<?= $article['id']; ?>">Ko'rish</a> |
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $article['user_id']): ?>
                <a href="edit.php?id=<?= $article['id']; ?>">Tahrirlash</a> |
                <form action="../controllers/DeleteController.php?id=<?= $article['id']; ?>" method="POST" style="display:inline;">
                    <button type="submit">O'chirish</button>
                </form>
            <?php endif; ?>
            <form action="../controllers/ReactionController.php" method="POST">
                <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
                <button type="submit" name="reaction" value="like">Like (<?= $article['likes']; ?>)</button>
                <button type="submit" name="reaction" value="dislike">Dislike (<?= $article['dislikes']; ?>)</button>
            </form>
        </div>
        <hr>
    <?php endforeach; ?>
</body>
</html>
