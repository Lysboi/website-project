<?php
session_start();
require_once '../includes/config.php';

if(isset($_POST['giris'])) {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];
    
    $stmt = $conn->prepare("SELECT id, kullanici_adi, sifre FROM kullanicilar WHERE kullanici_adi = ?");
    $stmt->execute([$kullanici_adi]);
    
    if($kullanici = $stmt->fetch()) {
        if(password_verify($sifre, $kullanici['sifre'])) {
            $_SESSION['admin_id'] = $kullanici['id'];
            $_SESSION['admin_kullanici'] = $kullanici['kullanici_adi'];
            header("Location: panel.php");
            exit;
        }
    }
    $hata = "Kullanıcı adı veya şifre hatalı!";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sed Nail Art - Admin Girişi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-96">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gold-500 mb-2" style="color: #D4AF37;">Sed Nail Art</h1>
            <p class="text-gray-400">Yönetim Paneli</p>
        </div>
        
        <?php if(isset($hata)): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
            <?php echo $hata; ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="kullanici_adi" class="block text-gray-400 mb-2">Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" id="kullanici_adi" required 
                    class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:border-gold-500 focus:outline-none"
                    style="focus:border-color: #D4AF37;">
            </div>

            <div>
                <label for="sifre" class="block text-gray-400 mb-2">Şifre</label>
                <input type="password" name="sifre" id="sifre" required 
                    class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:border-gold-500 focus:outline-none"
                    style="focus:border-color: #D4AF37;">
            </div>

            <button type="submit" name="giris" 
                class="w-full py-2 px-4 bg-gold-500 text-gray-900 rounded hover:bg-gold-600 focus:outline-none font-medium transition duration-300"
                style="background-color: #D4AF37; hover:background-color: #B4941F;">
                Giriş Yap
            </button>
        </form>
    </div>
</body>
</html>