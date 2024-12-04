<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// ID kontrolü
if(!isset($_GET['id'])) {
    header("Location: iletisim.php");
    exit;
}

$id = (int)$_GET['id'];

// İletişim bilgisi güncelleme
if(isset($_POST['iletisim_guncelle'])) {
    $tip = trim($_POST['tip']);
    $deger = trim($_POST['deger']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE iletisim_bilgileri SET tip = ?, deger = ?, aktif = ? WHERE id = ?");
    $stmt->execute([$tip, $deger, $aktif, $id]);
    $mesaj = "İletişim bilgisi başarıyla güncellendi!";
    $mesaj_tur = "success";
    
    // Güncel bilgileri çek
    $stmt = $conn->prepare("SELECT * FROM iletisim_bilgileri WHERE id = ?");
    $stmt->execute([$id]);
    $bilgi = $stmt->fetch();
} else {
    // İletişim bilgisini çek
    $stmt = $conn->prepare("SELECT * FROM iletisim_bilgileri WHERE id = ?");
    $stmt->execute([$id]);
    $bilgi = $stmt->fetch();
}

if(!$bilgi) {
    header("Location: iletisim.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim Bilgisi Düzenle - Sed Nail Art Yönetim Paneli</title>
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
                <a href="iletisim.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-address-book mr-3"></i>
                    İletişim Bilgileri
                </a>
                <a href="ayarlar.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-cog mr-3"></i>
                    Site Ayarları
                </a>
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">İletişim Bilgisi Düzenle</h2>
                <a href="iletisim.php" class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded-md text-white">
                    <i class="fas fa-arrow-left mr-2"></i> Geri Dön
                </a>
            </div>

            <?php if(isset($mesaj)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $mesaj_tur == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
                <?php echo $mesaj; ?>
            </div>
            <?php endif; ?>

            <!-- Düzenleme Formu -->
            <div class="bg-gray-800 rounded-lg p-6">
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Tür</label>
                        <select name="tip" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            <option value="telefon" <?php echo $bilgi['tip'] == 'telefon' ? 'selected' : ''; ?>>Telefon</option>
                            <option value="email" <?php echo $bilgi['tip'] == 'email' ? 'selected' : ''; ?>>E-posta</option>
                            <option value="adres" <?php echo $bilgi['tip'] == 'adres' ? 'selected' : ''; ?>>Adres</option>
                            <option value="whatsapp" <?php echo $bilgi['tip'] == 'whatsapp' ? 'selected' : ''; ?>>WhatsApp</option>
                            <option value="calisma_saatleri" <?php echo $bilgi['tip'] == 'calisma_saatleri' ? 'selected' : ''; ?>>Çalışma Saatleri</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Bilgi</label>
                        <textarea name="deger" required rows="4"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"><?php echo htmlspecialchars($bilgi['deger']); ?></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="aktif" id="aktif" class="mr-2"
                               <?php echo $bilgi['aktif'] ? 'checked' : ''; ?>>
                        <label for="aktif" class="text-sm text-gray-400">Aktif</label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="iletisim.php" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                            İptal
                        </a>
                        <button type="submit" name="iletisim_guncelle"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>