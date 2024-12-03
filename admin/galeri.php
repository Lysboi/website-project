<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Fotoğraf Yükleme
if(isset($_POST['fotograf_ekle'])) {
    $baslik = trim($_POST['baslik']);
    $kategori = trim($_POST['kategori']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    // Klasör kontrolü ve oluşturma
    $yukleme_dizini = "../assets/uploads/gallery/";
    if (!file_exists($yukleme_dizini)) {
        mkdir($yukleme_dizini, 0777, true);
    }

    if(isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $dosya_adi = uniqid() . "_" . basename($_FILES["resim"]["name"]);
        $hedef_dosya = $yukleme_dizini . $dosya_adi;
        
        // Sadece resim dosyalarına izin ver
        $dosya_tipi = strtolower(pathinfo($hedef_dosya, PATHINFO_EXTENSION));
        if(in_array($dosya_tipi, array("jpg", "jpeg", "png", "gif"))) {
            if(move_uploaded_file($_FILES["resim"]["tmp_name"], $hedef_dosya)) {
                $stmt = $conn->prepare("INSERT INTO galeri (baslik, resim, kategori, aktif) VALUES (?, ?, ?, ?)");
                $stmt->execute([$baslik, $dosya_adi, $kategori, $aktif]);
                $mesaj = "Fotoğraf başarıyla yüklendi!";
                $mesaj_tur = "success";
            } else {
                $mesaj = "Dosya yüklenirken bir hata oluştu.";
                $mesaj_tur = "error";
            }
        } else {
            $mesaj = "Sadece JPG, JPEG, PNG & GIF formatları kabul edilmektedir.";
            $mesaj_tur = "error";
        }
    }
}

// Fotoğraf Silme
if(isset($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    
    // Önce resim dosyasını bul
    $stmt = $conn->prepare("SELECT resim FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $fotograf = $stmt->fetch();
    
    if($fotograf) {
        // Dosyayı sil
        $dosya_yolu = "../assets/uploads/gallery/" . $fotograf['resim'];
        if(file_exists($dosya_yolu)) {
            unlink($dosya_yolu);
        }
        
        // Veritabanı kaydını sil
        $stmt = $conn->prepare("DELETE FROM galeri WHERE id = ?");
        $stmt->execute([$id]);
        $mesaj = "Fotoğraf başarıyla silindi!";
        $mesaj_tur = "success";
    }
}

// Fotoğrafları Listele
$stmt = $conn->query("SELECT * FROM galeri ORDER BY id DESC");
$fotograflar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Sed Nail Art Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .image-container {
            position: relative;
            padding-bottom: 100%; /* 1:1 aspect ratio */
        }
        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
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
                <h2 class="text-3xl font-bold">Galeri</h2>
                <button onclick="document.getElementById('yeniFotografModal').classList.remove('hidden')" 
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md text-white flex items-center">
                    <i class="fas fa-plus mr-2"></i> Yeni Fotoğraf Ekle
                </button>
            </div>

            <?php if(isset($mesaj)): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $mesaj_tur == 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
                <?php echo $mesaj; ?>
            </div>
            <?php endif; ?>

            <!-- Fotoğraf Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($fotograflar as $foto): ?>
                <div class="bg-gray-800 rounded-lg overflow-hidden">
                    <div class="image-container">
                        <img src="../assets/uploads/gallery/<?php echo htmlspecialchars($foto['resim']); ?>" 
                             alt="<?php echo htmlspecialchars($foto['baslik']); ?>"
                             class="rounded-t-lg">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold mb-2"><?php echo htmlspecialchars($foto['baslik']); ?></h3>
                        <p class="text-sm text-gray-400 mb-2">
                            Kategori: <?php echo htmlspecialchars($foto['kategori']); ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="<?php echo $foto['aktif'] ? 'text-green-500' : 'text-red-500'; ?>">
                                <?php echo $foto['aktif'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                            <div>
                                <a href="galeri_duzenle.php?id=<?php echo $foto['id']; ?>" 
                                   class="text-blue-500 hover:text-blue-400 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?sil=<?php echo $foto['id']; ?>" 
                                   onclick="return confirm('Bu fotoğrafı silmek istediğinizden emin misiniz?')"
                                   class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Yeni Fotoğraf Modal -->
    <div id="yeniFotografModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Yeni Fotoğraf Ekle</h3>
                <button onclick="document.getElementById('yeniFotografModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                    <input type="text" name="baslik" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Kategori</label>
                    <select name="kategori" required
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                        <option value="el_bakimi">El Bakımı</option>
                        <option value="ayak_bakimi">Ayak Bakımı</option>
                        <option value="protez_tirnak">Protez Tırnak</option>
                        <option value="kalici_oje">Kalıcı Oje</option>
                        <option value="nail_art">Nail Art</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Fotoğraf</label>
                    <input type="file" name="resim" required accept="image/*"
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                    <p class="text-sm text-gray-400 mt-1">Desteklenen formatlar: JPG, JPEG, PNG, GIF</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="aktif" id="aktif" class="mr-2" checked>
                    <label for="aktif" class="text-sm text-gray-400">Aktif</label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('yeniFotografModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                        İptal
                    </button>
                    <button type="submit" name="fotograf_ekle"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                        Fotoğraf Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>