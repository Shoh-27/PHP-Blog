<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Noto'g'ri email formati.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $email, $hashedPassword]);

        // ECHO YO'Q, HEADER ISHLASHI KERAK
        header('Location: ../views/index.php');
        exit();
    } catch (PDOException $e) {
        die("Xatolik yuz berdi: " . $e->getMessage());
    }
} else {
    echo "Noto'g'ri so'rov.";
}
?>
