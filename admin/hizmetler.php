<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Hizmet Ekleme
if(isset($_POST['hizmet_ekle'])) {
    $baslik = trim($_POST['baslik']);
    $aciklama = trim($_POST['aciklama']);
    $ikon = trim($_POST['ikon']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO hizmetler (baslik, aciklama, ikon, aktif) VALUES (?, ?, ?, ?)");
    $stmt->execute([$baslik, $aciklama, $ikon, $aktif]);
    $mesaj = "Hizmet başarıyla eklendi!";
    $mesaj_tur = "success";
}

// Hizmet Silme
if(isset($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    $stmt = $conn->prepare("DELETE FROM hizmetler WHERE id = ?");
    $stmt->execute([$id]);
    $mesaj = "Hizmet başarıyla silindi!";
    $mesaj_tur = "success";
}

// Hizmetleri Listele
$stmt = $conn->query("SELECT * FROM hizmetler ORDER BY id DESC");
$hizmetler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmetler - Sed Nail Art Yönetim Paneli</title>
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
                <a href="hizmetler.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
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
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Hizmetler</h2>
                <button onclick="document.getElementById('yeniHizmetModal').classList.remove('hidden')" 
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md text-white flex items-center">
                    <i class="fas fa-plus mr-2"></i> Yeni Hizmet Ekle
                </button>
            </div>

            <?php if(isset($mesaj)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $mesaj_tur == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
                <?php echo $mesaj; ?>
            </div>
            <?php endif; ?>

            <!-- Hizmetler Tablosu -->
            <div class="bg-gray-800 rounded-lg p-6">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left py-3 px-4 border-b border-gray-700">İkon</th>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Başlık</th>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Açıklama</th>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Durum</th>
                            <th class="text-right py-3 px-4 border-b border-gray-700">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($hizmetler as $hizmet): ?>
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <i class="<?php echo htmlspecialchars($hizmet['ikon']); ?>"></i>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php echo htmlspecialchars($hizmet['baslik']); ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php echo htmlspecialchars(substr($hizmet['aciklama'], 0, 100)) . '...'; ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php echo $hizmet['aktif'] ? 
                                    '<span class="bg-green-600 text-white px-2 py-1 rounded-full text-sm">Aktif</span>' : 
                                    '<span class="bg-red-600 text-white px-2 py-1 rounded-full text-sm">Pasif</span>'; ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 text-right">
                                <a href="hizmet_duzenle.php?id=<?php echo $hizmet['id']; ?>" 
                                   class="text-blue-500 hover:text-blue-400 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?sil=<?php echo $hizmet['id']; ?>" 
                                   onclick="return confirm('Bu hizmeti silmek istediğinizden emin misiniz?')"
                                   class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Yeni Hizmet Modal -->
    <div id="yeniHizmetModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Yeni Hizmet Ekle</h3>
                <button onclick="document.getElementById('yeniHizmetModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                    <input type="text" name="baslik" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Açıklama</label>
                    <textarea name="aciklama" required rows="4"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">İkon (Font Awesome class)</label>
                    <input type="text" name="ikon" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"
                           placeholder="örn: fas fa-gem">
                    <a href="https://fontawesome.com/icons" target="_blank" class="text-sm text-purple-400 hover:text-purple-300">
                        Font Awesome ikonlarını görüntüle
                    </a>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="aktif" id="aktif" class="mr-2">
                    <label for="aktif" class="text-sm text-gray-400">Aktif</label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('yeniHizmetModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                        İptal
                    </button>
                    <button type="submit" name="hizmet_ekle"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                        Hizmet Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>