<!-- form.html -->
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA misol</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- reCAPTCHA JS faylini yuklaymiz -->
</head>
<body>
    <h1>Formani to'ldiring</h1>
    <form action="verify.php" method="POST">
        <!-- Foydalanuvchi ma'lumotlari -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <!-- reCAPTCHA widgeti -->
        <div class="g-recaptcha" data-sitekey="6LdVppsqAAAAADxqU-C6kojmFW-uqXGvk_q7xqGG"></div><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
