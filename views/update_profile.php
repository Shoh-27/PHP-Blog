<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $biography = $_POST['biography'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Hozirgi foydalanuvchi parolini tekshirish
    $query = "SELECT password FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($current_password, $user['password'])) {
        // Parolni yangilash
        if (!empty($new_password)) {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE users SET password = :password WHERE id = :user_id";
            $stmt = $pdo->prepare($update_password_query);
            $stmt->bindParam(':password', $new_password_hashed);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }

        // Ism va biografiyani yangilash
        $update_query = "UPDATE users SET name = :name, biography = :biography WHERE id = :user_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':biography', $biography);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Profil rasmni yangilash
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $profile_picture = $_FILES['profile_picture'];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($profile_picture["name"]);
            move_uploaded_file($profile_picture["tmp_name"], $target_file);

            $update_picture_query = "UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id";
            $stmt = $pdo->prepare($update_picture_query);
            $stmt->bindParam(':profile_picture', $target_file);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }

        // Foydalanuvchini profilga yo'naltirish
        header("Location: profile.php");
        exit();
    } else {
        echo "Hozirgi parol noto'g'ri.";
    }
} else {
    echo "Noto'g'ri so'rov.";
}
?>
