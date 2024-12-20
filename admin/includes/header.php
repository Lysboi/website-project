<?php
session_start();
require_once '../../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit;
}

// Base URL'i belirle
$currentDir = dirname($_SERVER['PHP_SELF']);
$baseUrl = '';

// Eğer /pages/ klasöründeysek, bir üst dizine çık
if(strpos($currentDir, '/pages/') !== false) {
    $baseUrl = '../';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sed Nail Art - Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    
    <?php if($currentPage === 'sayfa_duzenle'): ?>
    <!-- Sayfa Düzenleme JS -->
    <script src="<?php echo $baseUrl; ?>js/sayfa/core.js"></script>
    <script src="<?php echo $baseUrl; ?>js/sayfa/forms.js"></script>
    <script src="<?php echo $baseUrl; ?>js/sayfa/handlers.js"></script>
    <?php endif; ?>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo $baseUrl; ?>panel.php" class="text-xl font-bold text-gold-500" style="color: #D4AF37;">
                        Sed Nail Art
                    </a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['admin_kullanici']); ?></span>
                    <a href="<?php echo $baseUrl; ?>cikis.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm">
                        Çıkış Yap
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex h-screen bg-gray-900">