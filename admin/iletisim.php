<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// İletişim Bilgisi Ekleme
if(isset($_POST['iletisim_ekle'])) {
    $tip = trim($_POST['tip']);
    $deger = trim($_POST['deger']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO iletisim_bilgileri (tip, deger, aktif) VALUES (?, ?, ?)");
    $stmt->execute([$tip, $deger, $aktif]);
    $mesaj = "İletişim bilgisi başarıyla eklendi!";
    $mesaj_tur = "success";
}

// İletişim Bilgisi Silme
if(isset($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    $stmt = $conn->prepare("DELETE FROM iletisim_bilgileri WHERE id = ?");
    $stmt->execute([$id]);
    $mesaj = "İletişim bilgisi başarıyla silindi!";
    $mesaj_tur = "success";
}

// İletişim Bilgilerini Listele
$stmt = $conn->query("SELECT * FROM iletisim_bilgileri ORDER BY id DESC");
$iletisim_bilgileri = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim Bilgileri - Sed Nail Art Yönetim Paneli</title>
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
                <h2 class="text-3xl font-bold">İletişim Bilgileri</h2>
                <button onclick="document.getElementById('yeniIletisimModal').classList.remove('hidden')" 
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md text-white flex items-center">
                    <i class="fas fa-plus mr-2"></i> Yeni Bilgi Ekle
                </button>
            </div>

            <?php if(isset($mesaj)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $mesaj_tur == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
                <?php echo $mesaj; ?>
            </div>
            <?php endif; ?>

            <!-- İletişim Bilgileri Tablosu -->
            <div class="bg-gray-800 rounded-lg p-6">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Tür</th>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Bilgi</th>
                            <th class="text-left py-3 px-4 border-b border-gray-700">Durum</th>
                            <th class="text-right py-3 px-4 border-b border-gray-700">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($iletisim_bilgileri as $bilgi): ?>
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php 
                                    $icon = '';
                                    switch($bilgi['tip']) {
                                        case 'telefon':
                                            $icon = 'fa-phone';
                                            $tip_text = 'Telefon';
                                            break;
                                        case 'email':
                                            $icon = 'fa-envelope';
                                            $tip_text = 'E-posta';
                                            break;
                                        case 'adres':
                                            $icon = 'fa-location-dot';
                                            $tip_text = 'Adres';
                                            break;
                                        case 'whatsapp':
                                            $icon = 'fa-whatsapp';
                                            $tip_text = 'WhatsApp';
                                            break;
                                        case 'calisma_saatleri':
                                            $icon = 'fa-clock';
                                            $tip_text = 'Çalışma Saatleri';
                                            break;
                                        default:
                                            $icon = 'fa-info-circle';
                                            $tip_text = ucfirst($bilgi['tip']);
                                    }
                                ?>
                                <i class="fas <?php echo $icon; ?> mr-2"></i>
                                <?php echo $tip_text; ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php echo nl2br(htmlspecialchars($bilgi['deger'])); ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700">
                                <?php echo $bilgi['aktif'] ? 
                                    '<span class="bg-green-600 text-white px-2 py-1 rounded-full text-sm">Aktif</span>' : 
                                    '<span class="bg-red-600 text-white px-2 py-1 rounded-full text-sm">Pasif</span>'; ?>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 text-right">
                                <a href="iletisim_duzenle.php?id=<?php echo $bilgi['id']; ?>" 
                                   class="text-blue-500 hover:text-blue-400 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?sil=<?php echo $bilgi['id']; ?>" 
                                   onclick="return confirm('Bu iletişim bilgisini silmek istediğinizden emin misiniz?')"
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

    <!-- Yeni İletişim Modal -->
    <div id="yeniIletisimModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Yeni İletişim Bilgisi Ekle</h3>
                <button onclick="document.getElementById('yeniIletisimModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Tür</label>
                    <select name="tip" required
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                        <option value="telefon">Telefon</option>
                        <option value="email">E-posta</option>
                        <option value="adres">Adres</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="calisma_saatleri">Çalışma Saatleri</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Bilgi</label>
                    <textarea name="deger" required rows="3"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="aktif" id="aktif" class="mr-2" checked>
                    <label for="aktif" class="text-sm text-gray-400">Aktif</label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('yeniIletisimModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                        İptal
                    </button>
                    <button type="submit" name="iletisim_ekle"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                        Bilgi Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>