<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Fotoğraf ID kontrolü
if(!isset($_GET['id'])) {
    header("Location: galeri.php");
    exit;
}

$id = (int)$_GET['id'];

// Fotoğraf güncelleme
if(isset($_POST['fotograf_guncelle'])) {
    $baslik = trim($_POST['baslik']);
    $kategori = trim($_POST['kategori']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    // Yeni resim yüklendi mi kontrol et
    if(isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $yukleme_dizini = "../assets/uploads/gallery/";
        $dosya_adi = uniqid() . "_" . basename($_FILES["resim"]["name"]);
        $hedef_dosya = $yukleme_dizini . $dosya_adi;
        
        // Dosya türü kontrolü
        $dosya_tipi = strtolower(pathinfo($hedef_dosya, PATHINFO_EXTENSION));
        if(in_array($dosya_tipi, array("jpg", "jpeg", "png", "gif"))) {
            // Eski resmi sil
            $stmt = $conn->prepare("SELECT resim FROM galeri WHERE id = ?");
            $stmt->execute([$id]);
            $eski_resim = $stmt->fetch();
            if($eski_resim && file_exists($yukleme_dizini . $eski_resim['resim'])) {
                unlink($yukleme_dizini . $eski_resim['resim']);
            }

            // Yeni resmi yükle
            if(move_uploaded_file($_FILES["resim"]["tmp_name"], $hedef_dosya)) {
                $stmt = $conn->prepare("UPDATE galeri SET baslik = ?, resim = ?, kategori = ?, aktif = ? WHERE id = ?");
                $stmt->execute([$baslik, $dosya_adi, $kategori, $aktif, $id]);
                $mesaj = "Fotoğraf başarıyla güncellendi!";
                $mesaj_tur = "success";
            } else {
                $mesaj = "Dosya yüklenirken bir hata oluştu.";
                $mesaj_tur = "error";
            }
        } else {
            $mesaj = "Sadece JPG, JPEG, PNG & GIF formatları kabul edilmektedir.";
            $mesaj_tur = "error";
        }
    } else {
        // Sadece diğer bilgileri güncelle
        $stmt = $conn->prepare("UPDATE galeri SET baslik = ?, kategori = ?, aktif = ? WHERE id = ?");
        $stmt->execute([$baslik, $kategori, $aktif, $id]);
        $mesaj = "Bilgiler başarıyla güncellendi!";
        $mesaj_tur = "success";
    }
    
    // Güncel fotoğraf bilgilerini çek
    $stmt = $conn->prepare("SELECT * FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $fotograf = $stmt->fetch();
} else {
    // Fotoğraf bilgilerini çek
    $stmt = $conn->prepare("SELECT * FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $fotograf = $stmt->fetch();
}

if(!$fotograf) {
    header("Location: galeri.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotoğraf Düzenle - Sed Nail Art Yönetim Paneli</title>
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
                <a href="galeri.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
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
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Fotoğraf Düzenle</h2>
                <a href="galeri.php" class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded-md text-white">
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
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Başlık</label>
                            <input type="text" name="baslik" required
                                   value="<?php echo htmlspecialchars($fotograf['baslik']); ?>"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Kategori</label>
                            <select name="kategori" required
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                                <option value="el_bakimi" <?php echo $fotograf['kategori'] == 'el_bakimi' ? 'selected' : ''; ?>>El Bakımı</option>
                                <option value="ayak_bakimi" <?php echo $fotograf['kategori'] == 'ayak_bakimi' ? 'selected' : ''; ?>>Ayak Bakımı</option>
                                <option value="protez_tirnak" <?php echo $fotograf['kategori'] == 'protez_tirnak' ? 'selected' : ''; ?>>Protez Tırnak</option>
                                <option value="kalici_oje" <?php echo $fotograf['kategori'] == 'kalici_oje' ? 'selected' : ''; ?>>Kalıcı Oje</option>
                                <option value="nail_art" <?php echo $fotograf['kategori'] == 'nail_art' ? 'selected' : ''; ?>>Nail Art</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Mevcut Fotoğraf</label>
                            <img src="../assets/uploads/gallery/<?php echo htmlspecialchars($fotograf['resim']); ?>" 
                                 alt="<?php echo htmlspecialchars($fotograf['baslik']); ?>"
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Yeni Fotoğraf (İsteğe bağlı)</label>
                            <input type="file" name="resim" accept="image/*"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                            <p class="text-sm text-gray-400 mt-1">Desteklenen formatlar: JPG, JPEG, PNG, GIF</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="aktif" id="aktif" class="mr-2"
                               <?php echo $fotograf['aktif'] ? 'checked' : ''; ?>>
                        <label for="aktif" class="text-sm text-gray-400">Aktif</label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="galeri.php" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                            İptal
                        </a>
                        <button type="submit" name="fotograf_guncelle"
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