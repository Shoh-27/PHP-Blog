<?php
session_start();
require_once '../config/database.php';

// Maqola ID'sini olish
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    try {
        // Maqolani olish
        $query = "SELECT articles.*, users.name AS author FROM articles 
                  JOIN users ON articles.user_id = users.id 
                  WHERE articles.id = :article_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            die("Maqola topilmadi.");
        }

        // Sharhlarni olish
        $comment_query = "SELECT comments.*, users.name AS commenter FROM comments 
                          JOIN users ON comments.user_id = users.id 
                          WHERE comments.article_id = :article_id 
                          ORDER BY comments.created_at ASC";
        $comment_stmt = $pdo->prepare($comment_query);
        $comment_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $comment_stmt->execute();
        $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
} else {
    die("Maqola ID topilmadi.");
}

// Reaktsiyalarni hisoblash funksiyasi
function getReactionCount($pdo, $comment_id, $reaction_type) {
    $query = "SELECT COUNT(*) FROM comment_reactions WHERE comment_id = :comment_id AND reaction = :reaction";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $stmt->bindParam(':reaction', $reaction_type, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Sharhlarni rekursiv tarzda chiqarish
function displayComments($pdo, $comments, $parent_id = 0, $level = 0) {
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            $likes = getReactionCount($pdo, $comment['id'], 'like');
            $dislikes = getReactionCount($pdo, $comment['id'], 'dislike');

            echo '<div class="comment" style="margin-left: ' . ($level * 40) . 'px;">';
            echo '<p><strong>' . htmlspecialchars($comment['commenter']) . '</strong></p>';
            echo '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
            echo '<small>Yaratilgan: ' . $comment['created_at'] . '</small>';
            echo "<p>👍 Like: $likes | 👎 Dislike: $dislikes</p>";

            if (isset($_SESSION['user_id'])) {
                echo '<form method="POST" action="../controllers/CommentReactionController.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="' . $comment['id'] . '">
                        <input type="hidden" name="article_id" value="' . $comment['article_id'] . '">
                        <button type="submit" name="reaction" value="like">👍 Like</button>
                        <button type="submit" name="reaction" value="dislike">👎 Dislike</button>
                      </form>';

                echo '<button onclick="showReplyForm(' . $comment['id'] . ')">Reply</button>';
                echo '<div id="reply-form-' . $comment['id'] . '" style="display: none; margin-top: 10px;">
                        <form method="POST" action="add_comment.php">
                            <input type="hidden" name="article_id" value="' . $comment['article_id'] . '">
                            <input type="hidden" name="parent_id" value="' . $comment['id'] . '">
                            <textarea name="content" placeholder="Javob yozing..." required></textarea><br>
                            <button type="submit">Javob yuborish</button>
                        </form>
                      </div>';
            }

            echo '</div><hr>';
            displayComments($pdo, $comments, $comment['id'], $level + 1);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="/assets/show.css">
    <script>
        function showReplyForm(commentId) {
            document.querySelectorAll('[id^="reply-form-"]').forEach(form => form.style.display = 'none');
            document.getElementById('reply-form-' + commentId).style.display = 'block';
        }
    </script>
</head>
<body>
    <h1><?= htmlspecialchars($article['title']); ?></h1>
    <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>
    <small>Yozuvchi: <?= htmlspecialchars($article['author']); ?> | <?= $article['created_at']; ?></small>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Sharh qoldirish</h3>
        <form method="POST" action="add_comment.php">
            <textarea name="content" placeholder="Sharh yozing..." required></textarea><br>
            <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
            <button type="submit">Yuborish</button>
        </form>
    <?php else: ?>
        <p>Sharh qoldirish uchun <a href="login.php">kirish</a> kerak.</p>
    <?php endif; ?>

    <h2>Sharhlar</h2>
    <?php if ($comments): ?>
        <?php displayComments($pdo, $comments); ?>
    <?php else: ?>
        <p>Bu maqolada sharhlar mavjud emas.</p>
    <?php endif; ?>
</body>
</html>
