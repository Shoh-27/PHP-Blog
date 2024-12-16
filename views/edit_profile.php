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

if (!$user) {
    die("Foydalanuvchi topilmadi.");
}

// Profilni tahrirlash
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $biography = $_POST['biography'] ?? '';
    $password = $_POST['password'] ?? '';

    // Yangi parol bo'lsa, uni yangilash
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET name = :name, email = :email, biography = :biography, password = :password WHERE id = :user_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':password', $password);
    } else {
        $update_query = "UPDATE users SET name = :name, email = :email, biography = :biography WHERE id = :user_id";
        $stmt = $pdo->prepare($update_query);
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':biography', $biography);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilni tahrirlash</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Profilni tahrirlash</h1>

    <form action="edit_profile.php" method="POST">
        <label for="name">Ism:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name'] ?? ''); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required><br>

        <label for="biography">Biografiya:</label>
        <textarea name="biography" id="biography" rows="4" required><?= htmlspecialchars($user['biography'] ?? ''); ?></textarea><br>

        <label for="password">Parol (o'zgartirmoqchi bo'lsangiz kiriting):</label>
        <input type="password" name="password" id="password"><br>

        <button type="submit">Tahrirlash</button>
    </form>

    <a href="profile.php">Orqaga</a>
</body>
</html>
