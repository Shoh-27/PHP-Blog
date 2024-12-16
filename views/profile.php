<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Foydalanuvchi ma'lumotlarini olish
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ma'lumotlar bo'sh bo'lsa, `null`ni tekshirish
if (!$user) {
    die("Foydalanuvchi topilmadi.");
}

// Maqolalar va sharhlarni olish
$articles_query = "SELECT * FROM articles WHERE user_id = :user_id";
$articles_stmt = $pdo->prepare($articles_query);
$articles_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$articles_stmt->execute();
$articles = $articles_stmt->fetchAll(PDO::FETCH_ASSOC);

$comments_query = "SELECT * FROM comments WHERE user_id = :user_id";
$comments_stmt = $pdo->prepare($comments_query);
$comments_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$comments_stmt->execute();
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

// Reaktsiyalarni olish
$reactions_query = "SELECT * FROM comment_reactions WHERE user_id = :user_id";
$reactions_stmt = $pdo->prepare($reactions_query);
$reactions_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$reactions_stmt->execute();
$reactions = $reactions_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Profil</h1>

    <p><strong>Ism:</strong> <?= htmlspecialchars($user['name'] ?? ''); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? ''); ?></p>
    <p><strong>Biografiya:</strong> <?= nl2br(htmlspecialchars($user['biography'] ?? '')); ?></p>

    <p><strong>Ro'yxatdan o'tgan sana:</strong> <?= $user['created_at']; ?></p>

    <h3>Yozgan maqolalaringiz</h3>
    <?php if (count($articles) > 0): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li><a href="show.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title'] ?? ''); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Siz hali maqola yozmagansiz.</p>
    <?php endif; ?>

    <h3>Qoldirgan sharhlaringiz</h3>
    <?php if (count($comments) > 0): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li><?= htmlspecialchars($comment['content'] ?? ''); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Siz hali sharh qoldirmagansiz.</p>
    <?php endif; ?>

    <h3>Like va Dislike bergan sharhlaringiz</h3>
    <?php if (count($reactions) > 0): ?>
        <ul>
            <?php foreach ($reactions as $reaction): ?>
                <li>
                    <?= htmlspecialchars($reaction['reaction'] ?? ''); ?> 
                    - <?= htmlspecialchars($reaction['comment_id'] ?? ''); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Siz hali sharhlarga like yoki dislike bermagansiz.</p>
    <?php endif; ?>

    <a href="edit_profile.php">Profilni tahrirlash</a> | <a href="../controllers/LogoutController.php">Chiqish</a>
</body>
</html>
