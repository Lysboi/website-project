<?php
session_start();
require_once '../includes/config.php';

// Kullanıcı girişi yapılmış mı kontrol et
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
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
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="text-xl font-bold text-gold-500" style="color: #D4AF37;">
                        Sed Nail Art
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['admin_kullanici']); ?></span>
                    <a href="cikis.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm">
                        Çıkış Yap
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Ana İçerik -->
    <div class="flex h-screen bg-gray-900">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800">
            <nav class="mt-5">
                <a href="panel.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-home mr-3"></i>
                    Ana Sayfa
                </a>
                <a href="hizmetler.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-concierge-bell mr-3"></i>
                    Hizmetler
                </a>
                <a href="galeri.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-images mr-3"></i>
                    Galeri
                </a>
                <a href="iletisim.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-address-book mr-3"></i>
                    İletişim Bilgileri
                </a>
                <a href="ayarlar.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-cog mr-3"></i>
                    Site Ayarları
                </a>
                <a href="sayfa_duzenle.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-edit mr-3"></i>
                    Sayfa Düzenle
                </a>
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10">
            <h2 class="text-3xl font-bold mb-8">Yönetim Paneli</h2>
            
            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-25">
                            <i class="fas fa-concierge-bell text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold">Toplam Hizmet</h4>
                            <p class="text-2xl font-bold">0</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-25">
                            <i class="fas fa-images text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold">Galeri Görseli</h4>
                            <p class="text-2xl font-bold">0</p>
                        </div>
                    </div>
                </div>

                <!-- Diğer istatistik kartları buraya eklenebilir -->
            </div>

            <!-- Hızlı İşlemler -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">Hızlı İşlemler</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="hizmetler.php?islem=ekle" class="flex items-center p-4 bg-gray-700 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Yeni Hizmet Ekle
                    </a>
                    <a href="galeri.php?islem=ekle" class="flex items-center p-4 bg-gray-700 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-image mr-3"></i>
                        Galeriye Fotoğraf Ekle
                    </a>
                    <a href="iletisim.php" class="flex items-center p-4 bg-gray-700 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-edit mr-3"></i>
                        İletişim Bilgilerini Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>