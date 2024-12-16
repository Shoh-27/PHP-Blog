<?php
$host = 'localhost';
$db_name = 'blog_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Baza bilan ulanishda xatolik: " . $e->getMessage());
}
?>
