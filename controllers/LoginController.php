<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Email bo'yicha foydalanuvchini topish
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Sessiyani boshlash
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Bosh sahifaga yo'naltirish
            header('Location: ../views/index.php');
            exit();
        } else {
            echo "Email yoki parol noto'g'ri!";
        }
    } catch (PDOException $e) {
        die("Xatolik: " . $e->getMessage());
    }
} else {
    echo "Noto'g'ri so'rov.";
}
?>
