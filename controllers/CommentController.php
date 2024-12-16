<?php
// Maqolani ko'rsatish uchun maqola ID'sini olish
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Comments jadvalidan sharhlarni olish
    $query = "SELECT comments.*, users.name AS author FROM comments 
              JOIN users ON comments.user_id = users.id 
              WHERE comments.article_id = :article_id 
              ORDER BY comments.created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sharhlarni chiqarish
    if ($comments) {
        foreach ($comments as $comment) {
            echo "<div>";
            echo "<p><strong>" . htmlspecialchars($comment['author']) . ":</strong></p>";
            echo "<p>" . nl2br(htmlspecialchars($comment['content'])) . "</p>";
            echo "<small>Yaratilgan: " . $comment['created_at'] . "</small>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>Bu maqolada sharhlar mavjud emas.</p>";
    }
} else {
    echo "<p>Maqola topilmadi.</p>";
}
?>
