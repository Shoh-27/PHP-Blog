<?php
session_start();
require_once '../config/database.php';

// Foydalanuvchi autentifikatsiya qilinganligini tekshirish
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// GET so'rovi bilan maqola ma'lumotlarini yuklash
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $article_id = intval($_GET['id']);
    try {
        $query = "SELECT * FROM articles WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $article_id, 'user_id' => $_SESSION['user_id']]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            die("Maqola topilmadi yoki sizda ruxsat yo'q.");
        }
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
}

// POST so'rovi bilan maqolani yangilash
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $article_id = intval($_POST['id']);
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $query = "UPDATE articles SET title = :title, content = :content WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id' => $article_id,
            'user_id' => $_SESSION['user_id']
        ]);

        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Maqolani tahrirlash</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Maqolani tahrirlash</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($article['id']); ?>">
        <label for="title">Sarlavha:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($article['title']); ?>" required>
        <br>
        <label for="content">Mazmun:</label>
        <textarea name="content" id="content" required><?= htmlspecialchars($article['content']); ?></textarea>
        <br>
        <button type="submit">Saqlash</button>
    </form>
</body>
</html>
