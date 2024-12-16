<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ro'yxatdan o'tish</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Keyin CSS qo'shamiz -->
</head>
<body>
    <h2>Ro'yxatdan o'tish</h2>
    <form action="../controllers/RegisterController.php" method="POST">
        <label for="name">Ism:</label><br>
        <input type="text" name="name" id="name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Parol:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Ro'yxatdan o'tish</button>
    </form>
</body>
</html>
