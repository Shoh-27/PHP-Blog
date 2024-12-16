<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recaptcha_secret = '6LdVppsqAAAAADxqU-C6kojmFW-uqXGvk_q7xqGG'; // O'zingizning Secret Keyni qo'ying
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // cURL bilan Google reCAPTCHA API'ga so'rov yuborish
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response,
    ];

    // cURL sozlash
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Yuborish va javobni olish
    $response = curl_exec($ch);
    curl_close($ch);

    // Javobni JSON formatida qabul qilish
    $response_keys = json_decode($response, true);

    // Agar reCAPTCHA muvaffaqiyatli bo'lsa
    if (intval($response_keys["success"]) !== 1) {
        echo "reCAPTCHA tekshiruvi muvaffaqiyatsiz! Iltimos, qayta urinib ko'ring.";
    } else {
        echo "reCAPTCHA muvaffaqiyatli bo'ldi! Formani yubordik.";
        // Boshqa formani ishlashni davom ettirishingiz mumkin, masalan: foydalanuvchi ma'lumotlarini saqlash.
    }
}
?>
