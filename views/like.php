<?php
// Like qo'shish
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['user_id'];

    // Maqola uchun like bor yoki yo'qligini tekshirish
    $query = "SELECT * FROM likes WHERE article_id = :article_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $query = "UPDATE likes SET like_status = 1 WHERE article_id = :article_id AND user_id = :user_id";
    } else {
        $query = "INSERT INTO likes (article_id, user_id, like_status) VALUES (:article_id, :user_id, 1)";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}
?>
<form method="POST" action="index.php">
    <input type="hidden" name="article_id" value="<?= $article_id ?>">
    <button type="submit">Like</button>
</form>
