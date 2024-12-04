<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Ayarları Güncelle
if(isset($_POST['ayarlar_kaydet'])) {
    $ayarlar = [
        'site_baslik' => trim($_POST['site_baslik']),
        'site_aciklama' => trim($_POST['site_aciklama']),
        'instagram' => trim($_POST['instagram']),
        'facebook' => trim($_POST['facebook']),
        'tiktok' => trim($_POST['tiktok']),
        'ana_sayfa_baslik' => trim($_POST['ana_sayfa_baslik']),
        'ana_sayfa_aciklama' => trim($_POST['ana_sayfa_aciklama'])
    ];

    foreach($ayarlar as $anahtar => $deger) {
        // Önce ayarın var olup olmadığını kontrol et
        $stmt = $conn->prepare("SELECT COUNT(*) FROM ayarlar WHERE anahtar = ?");
        $stmt->execute([$anahtar]);
        $varMi = $stmt->fetchColumn();

        if($varMi) {
            // Güncelle
            $stmt = $conn->prepare("UPDATE ayarlar SET deger = ? WHERE anahtar = ?");
            $stmt->execute([$deger, $anahtar]);
        } else {
            // Yeni ekle
            $stmt = $conn->prepare("INSERT INTO ayarlar (anahtar, deger) VALUES (?, ?)");
            $stmt->execute([$anahtar, $deger]);
        }
    }

    $mesaj = "Ayarlar başarıyla güncellendi!";
    $mesaj_tur = "success";
}

// Mevcut ayarları çek
$stmt = $conn->query("SELECT * FROM ayarlar");
$mevcut_ayarlar = [];
while($ayar = $stmt->fetch()) {
    $mevcut_ayarlar[$ayar['anahtar']] = $ayar['deger'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Ayarları - Sed Nail Art Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="panel.php" class="text-xl font-bold text-gold-500" style="color: #D4AF37;">
                        Sed Nail Art
                    </a>
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
                <a href="ayarlar.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-cog mr-3"></i>
                    Site Ayarları
                </a>
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Site Ayarları</h2>
            </div>

            <?php if(isset($mesaj)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $mesaj_tur == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
                <?php echo $mesaj; ?>
            </div>
            <?php endif; ?>

            <!-- Ayarlar Formu -->
            <div class="bg-gray-800 rounded-lg p-6">
                <form method="POST" class="space-y-6">
                    <!-- Genel Ayarlar -->
                    <div class="border-b border-gray-700 pb-6">
                        <h3 class="text-xl font-semibold mb-4">Genel Ayarlar</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Site Başlığı</label>
                                <input type="text" name="site_baslik" 
                                       value="<?php echo htmlspecialchars($mevcut_ayarlar['site_baslik'] ?? ''); ?>"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Site Açıklaması</label>
                                <textarea name="site_aciklama" rows="3"
                                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"><?php echo htmlspecialchars($mevcut_ayarlar['site_aciklama'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sosyal Medya -->
                    <div class="border-b border-gray-700 pb-6">
                        <h3 class="text-xl font-semibold mb-4">Sosyal Medya</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">
                                    <i class="fab fa-instagram mr-2"></i>Instagram
                                </label>
                                <input type="text" name="instagram" 
                                       value="<?php echo htmlspecialchars($mevcut_ayarlar['instagram'] ?? ''); ?>"
                                       placeholder="Instagram kullanıcı adı"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">
                                    <i class="fab fa-facebook mr-2"></i>Facebook
                                </label>
                                <input type="text" name="facebook" 
                                       value="<?php echo htmlspecialchars($mevcut_ayarlar['facebook'] ?? ''); ?>"
                                       placeholder="Facebook sayfası URL'i"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">
                                    <i class="fab fa-tiktok mr-2"></i>TikTok
                                </label>
                                <input type="text" name="tiktok" 
                                       value="<?php echo htmlspecialchars($mevcut_ayarlar['tiktok'] ?? ''); ?>"
                                       placeholder="TikTok kullanıcı adı"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Ana Sayfa Ayarları -->
                    <div class="border-b border-gray-700 pb-6">
                        <h3 class="text-xl font-semibold mb-4">Ana Sayfa Ayarları</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Ana Sayfa Başlığı</label>
                                <input type="text" name="ana_sayfa_baslik" 
                                       value="<?php echo htmlspecialchars($mevcut_ayarlar['ana_sayfa_baslik'] ?? ''); ?>"
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Ana Sayfa Açıklaması</label>
                                <textarea name="ana_sayfa_aciklama" rows="3"
                                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"><?php echo htmlspecialchars($mevcut_ayarlar['ana_sayfa_aciklama'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" name="ayarlar_kaydet"
                                class="px-6 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                            <i class="fas fa-save mr-2"></i>
                            Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>