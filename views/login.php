<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Login</h2>
    <form action="../controllers/LoginController.php" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Parol:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Kirish</button>
    </form>
</body>
</html>
