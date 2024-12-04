<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Düzenle - Sed Nail Art Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
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
                <a href="ayarlar.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700">
                    <i class="fas fa-cog mr-3"></i>
                    Site Ayarları
                </a>
                <a href="sayfa_duzenle.php" class="flex items-center px-6 py-3 bg-gray-700 text-white">
                    <i class="fas fa-edit mr-3"></i>
                    Sayfa Düzenle
                </a>
            </nav>
        </div>

        <!-- İçerik Alanı -->
        <div class="flex-1 p-10 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Sayfa Düzenle</h2>
                <button onclick="yeniBolumEkle()" 
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md text-white flex items-center">
                    <i class="fas fa-plus mr-2"></i> Yeni Bölüm Ekle
                </button>
            </div>

            <!-- Bölümler Listesi -->
            <div class="space-y-4" id="bolumlerListesi">
                <?php foreach($bolumler as $bolum): 
                    $ayarlar = json_decode($bolum['ayarlar'], true);
                ?>
                <div class="bg-gray-800 rounded-lg p-4 cursor-move relative group" data-id="<?php echo $bolum['id']; ?>">
                    <div class="absolute left-0 top-0 bottom-0 w-8 flex items-center justify-center cursor-move bg-gray-700 rounded-l-lg">
                        <i class="fas fa-grip-vertical text-gray-400"></i>
                    </div>
                    <div class="flex items-center justify-between pl-8">
                        <div class="flex items-center space-x-4">
                            <?php 
                                $icon = '';
                                switch($bolum['tip']) {
                                    case 'hero': $icon = 'fa-image'; break;
                                    case 'metin': $icon = 'fa-text'; break;
                                    case 'hizmetler': $icon = 'fa-list'; break;
                                    case 'galeri': $icon = 'fa-images'; break;
                                }
                            ?>
                            <i class="fas <?php echo $icon; ?> text-gray-400"></i>
                            <div>
                                <h3 class="font-semibold"><?php echo htmlspecialchars($bolum['baslik']); ?></h3>
                                <span class="text-sm text-gray-400"><?php echo htmlspecialchars($ayarlar['baslik'] ?? ''); ?></span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" <?php echo $bolum['aktif'] ? 'checked' : ''; ?>
                                       onchange="durumDegistir(<?php echo $bolum['id']; ?>, this.checked)">
                                <div class="w-11 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-purple-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                            <button onclick="bolumDuzenle(<?php echo $bolum['id']; ?>, '<?php echo $bolum['tip']; ?>')"
                                    class="text-blue-500 hover:text-blue-400">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="bolumSil(<?php echo $bolum['id']; ?>)"
                                    class="text-red-500 hover:text-red-400">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php if(!$bolum['aktif']): ?>
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                        <span class="bg-red-600 px-3 py-1 rounded-full text-sm">Pasif</span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Ön İzleme Butonu -->
            <div class="fixed bottom-6 right-6">
                <a href="../" target="_blank" 
                   class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-full text-white shadow-lg flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    Siteyi Görüntüle
                </a>
            </div>
        </div>
    </div>

    <!-- Yeni Bölüm Modal -->
    <div id="yeniBolumModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-8 rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Yeni Bölüm Ekle</h3>
                <button onclick="document.getElementById('yeniBolumModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form onsubmit="return bolumEkleSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Bölüm İsmi (Panel için)</label>
                    <input type="text" name="bolum_baslik" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"
                           placeholder="Örn: Üst Bölüm, Alt Galeri vb.">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Bölüm Tipi</label>
                    <select name="bolum_tip" required
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                        <option value="hero">Hero Bölümü (Tam Ekran Başlık)</option>
                        <option value="metin">Metin Bölümü</option>
                        <option value="hizmetler">Hizmetler Bölümü</option>
                        <option value="galeri">Galeri Bölümü</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('yeniBolumModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                        Bölüm Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Düzenleme Modal -->
    <div id="duzenleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-8 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Bölüm Düzenle</h3>
                <button onclick="document.getElementById('duzenleModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="duzenleForm" onsubmit="return bolumGuncelleSubmit(event)" class="space-y-4">
                <!-- Form içeriği JavaScript ile doldurulacak -->
            </form>
        </div>
    </div>
</body>
</html>